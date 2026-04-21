<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StockVoucherController extends Controller
{
    public function store(Request $request)
    {
        $hasInvoiceNumber = $request->filled('invoice_number');
        $tempVoucherNumber = 'TMP-' . Str::uuid()->toString();

        $request->merge(['voucher_number' => $tempVoucherNumber]);
        if (!$hasInvoiceNumber) {
            $request->merge(['invoice_number' => $tempVoucherNumber]);
        }

        if (!$request->filled('invoice_date')) {
            $request->merge(['invoice_date' => now()->toDateString()]);
        }
        if (!$request->filled('payment_mode')) {
            $request->merge(['payment_mode' => 'Cash']);
        }

        $request->validate([
            'voucher_number' => 'required|unique:stock_vouchers,voucher_number',
            'invoice_number' => 'required|unique:stock_vouchers,invoice_number',
            'invoice_date' => 'required|date',
            'payment_mode' => 'required|in:Cash,Cheque,Online Transfer,Card',
            'party_name' => 'required|string|max:255|regex:/^[A-Za-z0-9][A-Za-z0-9\s\.\&\'\-\,]*$/',
            'address' => 'required|string|max:500',
            'phone_number' => 'required|regex:/^[0-9]{10}$/',
            'tax_number' => 'required|string|max:50',
            'vehicle_number' => 'required|string|max:50',
            'density' => 'nullable|numeric',
            'temperature' => 'nullable|numeric',
            'fbp_chamber' => 'nullable|numeric',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
        ]);

        try {
            $chambers = $request->input('chambers');
            if (empty($chambers)) {
                $chambers = [];
            }
            if (is_array($chambers)) {
                $chambers = json_encode($chambers);
            }

            $items = $request->input('items', []);
            if (is_string($items)) {
                $items = json_decode($items, true) ?? [];
            }
            if (!is_array($items)) {
                $items = [];
            }

            $density = $request->filled('density') ? (float) $request->input('density') : null;
            $temperature = $request->filled('temperature') ? (float) $request->input('temperature') : null;
            $fbpChamber = $request->filled('fbp_chamber') ? (float) $request->input('fbp_chamber') : null;

            $stockVoucherId = DB::transaction(function () use ($request, $chambers, $items, $hasInvoiceNumber, $density, $temperature, $fbpChamber) {
                $stockVoucherId = DB::table('stock_vouchers')->insertGetId([
                    'voucher_number' => $request->input('voucher_number'),
                    'invoice_number' => $request->input('invoice_number'),
                    'invoice_date' => $request->input('invoice_date'),
                    'payment_mode' => $request->input('payment_mode'),
                    'party_name' => $request->input('party_name'),
                    'address' => $request->input('address'),
                    'phone_number' => $request->input('phone_number'),
                    'tax_number' => $request->input('tax_number'),
                    'vehicle_number' => $request->input('vehicle_number'),
                    'density' => $density,
                    'temperature' => $temperature,
                    'fbp_chamber' => $fbpChamber,
                    'chambers' => $chambers,
                    'subtotal' => $request->input('subtotal'),
                    'extra_charge' => $request->input('extra_charge') ?? 0,
                    'rounding' => $request->input('rounding') ?? 0,
                    'before_tax_total' => $request->input('before_tax_total') ?? $request->input('subtotal'),
                    'tax_amount' => $request->input('tax_amount'),
                    'total_amount' => $request->input('total_amount'),
                    'status' => 'completed',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $voucherNumber = $this->formatVoucherNumber($stockVoucherId);
                $updatePayload = [
                    'voucher_number' => $voucherNumber,
                    'updated_at' => now(),
                ];
                if (!$hasInvoiceNumber) {
                    $updatePayload['invoice_number'] = $voucherNumber;
                }

                DB::table('stock_vouchers')
                    ->where('id', $stockVoucherId)
                    ->update($updatePayload);

                $fuelMap = [
                    'MS' => 'Petrol',
                    'HSD' => 'Diesel',
                    'LUB' => 'Lubricants',
                ];

                foreach ($items as $item) {
                    $fuelCode = $item['product'] ?? null;
                    $qty = isset($item['qty']) ? (float) $item['qty'] : 0.0;
                    $rate = isset($item['rate']) ? (float) $item['rate'] : 0.0;
                    $discount = isset($item['discount']) ? (float) $item['discount'] : 0.0;
                    $amount = isset($item['amount']) ? (float) $item['amount'] : 0.0;

                    if (!$fuelCode || $qty <= 0) {
                        continue;
                    }

                    DB::table('stock_voucher_items')->insert([
                        'stock_voucher_id' => $stockVoucherId,
                        'fuel_type' => $fuelCode,
                        'quantity' => $qty,
                        'unit_rate' => $rate,
                        'discount' => $discount,
                        'amount' => $amount,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $stock = Stock::firstOrNew(['fuel_code' => $fuelCode]);
                    if (!$stock->exists) {
                        $stock->fuel_type = $fuelMap[$fuelCode] ?? $fuelCode;
                        $stock->current_quantity = 0;
                    }

                    $stock->current_quantity = ($stock->current_quantity ?? 0) + $qty;
                    if ($rate > 0) {
                        $stock->unit_price = $rate;
                    }
                    $stock->total_value = ($stock->current_quantity ?? 0) * ($stock->unit_price ?? 0);
                    $stock->save();
                }

                return $stockVoucherId;
            });

            $voucherNumber = $this->formatVoucherNumber($stockVoucherId);
            $nextVoucherNumber = $this->formatVoucherNumber($stockVoucherId + 1);
        } catch (\Exception $e) {
            Log::error('Failed to insert stock_voucher', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to save voucher', 'error' => $e->getMessage()], 500);
            }

            return back()->with('error', 'Failed to save voucher. Check logs.');
        }

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Voucher saved successfully',
                'data' => [
                    'voucher_number' => $voucherNumber,
                    'next_voucher_number' => $nextVoucherNumber,
                ],
            ]);
        }

        return back()->with('success', 'Voucher saved successfully');
    }

    private function formatVoucherNumber(int $serial): string
    {
        return 'V' . ($serial + 1000);
    }
}