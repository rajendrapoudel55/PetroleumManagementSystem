<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaxInvoice;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TaxInvoiceController extends Controller
{
    private function parseItems(string $itemsJson): array
    {
        $items = json_decode($itemsJson, true);
        if (!is_array($items)) {
            throw new \RuntimeException('Invalid invoice items data.');
        }

        return $items;
    }

    private function resolveInventoryTarget(array $item, bool $strict = true): array
    {
        $productId = (string) ($item['product_id'] ?? '');
        $sku = trim((string) ($item['sku'] ?? ''));
        $name = trim((string) ($item['product'] ?? ''));

        if ($productId !== '') {
            if (str_starts_with($productId, 'stock_')) {
                $id = (int) str_replace('stock_', '', $productId);
                $stock = Stock::where('id', $id)->lockForUpdate()->first();
                if ($stock) {
                    return ['type' => 'stock', 'model' => $stock];
                }
            }

            if (str_starts_with($productId, 'prod_')) {
                $id = (int) str_replace('prod_', '', $productId);
                $product = Product::where('id', $id)->lockForUpdate()->first();
                if ($product) {
                    return ['type' => 'product', 'model' => $product];
                }
            }
        }

        if ($sku !== '') {
            $stock = Stock::whereRaw('LOWER(fuel_code) = ?', [strtolower($sku)])->lockForUpdate()->first();
            if ($stock) {
                return ['type' => 'stock', 'model' => $stock];
            }

            $product = Product::whereRaw('LOWER(sku) = ?', [strtolower($sku)])->lockForUpdate()->first();
            if ($product) {
                return ['type' => 'product', 'model' => $product];
            }
        }

        if ($name !== '') {
            $stock = Stock::whereRaw('LOWER(fuel_type) = ?', [strtolower($name)])->lockForUpdate()->first();
            if ($stock) {
                return ['type' => 'stock', 'model' => $stock];
            }

            $product = Product::whereRaw('LOWER(product_name) = ?', [strtolower($name)])->lockForUpdate()->first();
            if ($product) {
                return ['type' => 'product', 'model' => $product];
            }
        }

        if (!$strict) {
            return ['type' => null, 'model' => null];
        }

        throw new \RuntimeException('Product not found in inventory: ' . ($name !== '' ? $name : 'unknown item'));
    }

    private function adjustInventory(array $items, int $direction, bool $strict = true): void
    {
        foreach ($items as $item) {
            $qty = (float) ($item['qty'] ?? 0);
            if ($qty <= 0) {
                continue;
            }

            $target = $this->resolveInventoryTarget($item, $strict);
            if (!$target['model']) {
                continue;
            }

            if ($target['type'] === 'stock') {
                /** @var \App\Models\Stock $stock */
                $stock = $target['model'];
                $current = (float) $stock->current_quantity;

                if ($direction < 0 && $current < $qty) {
                    throw new \RuntimeException('Insufficient stock for ' . $stock->fuel_type . '. Available: ' . $current . ', required: ' . $qty);
                }

                $stock->current_quantity = $current + ($direction * $qty);
                $stock->total_value = (float) $stock->current_quantity * (float) $stock->unit_price;
                $stock->save();
                continue;
            }

            /** @var \App\Models\Product $product */
            $product = $target['model'];
            $current = (float) $product->stock_quantity;

            if ($direction < 0 && $current < $qty) {
                throw new \RuntimeException('Insufficient stock for ' . $product->product_name . '. Available: ' . $current . ', required: ' . $qty);
            }

            $product->stock_quantity = $current + ($direction * $qty);
            $product->save();
        }
    }

    /**
     * Show invoice page
     */
    public function index()
    {
        return view('Taxinvoice');
    }

    /**
     * Get all invoices as JSON
     */
    public function getAll()
    {
        $invoices = TaxInvoice::latest()->get()
            ->map(fn($i) => array_merge($i->toArray(), ['__backendId' => $i->id]));

        return response()->json(['isOk' => true, 'data' => $invoices]);
    }

    /**
     * Store new invoice
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bill_number'      => 'required|string|unique:tax_invoices,bill_number',
            'date'             => 'required|date',
            'customer_name'    => 'nullable|string|max:255|regex:/^[A-Za-z][A-Za-z\s\.\'\-]*$/',
            'phone'            => 'nullable|regex:/^[0-9]{10}$/',
            'vehicle'          => 'nullable|string|max:50',
            'payment_method'   => 'required|in:Khalti,Cash,Credit',
            'transaction_no'   => 'nullable|string|max:255|required_if:payment_method,Khalti',
            'items_json'       => 'required|string',
            'subtotal'         => 'required|numeric|min:0',
            'gst'              => 'required|numeric|min:0',
            'total'            => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'isOk' => false,
                'error' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        try {
            $items = $this->parseItems($validated['items_json']);
            if (count($items) === 0) {
                return response()->json([
                    'isOk' => false,
                    'error' => 'At least one invoice item is required.',
                ], 422);
            }

            $invoice = DB::transaction(function () use ($validated, $items) {
                $invoice = TaxInvoice::create($validated);
                $this->adjustInventory($items, -1, true);

                return $invoice;
            });
        } catch (\Throwable $e) {
            return response()->json([
                'isOk' => false,
                'error' => $e->getMessage() ?: 'Unable to save bill right now. Please try again.',
            ], 500);
        }

        return response()->json([
            'isOk' => true,
            'data' => array_merge($invoice->toArray(), ['__backendId' => $invoice->id])
        ]);
    }

    /**
     * Update invoice
     */
    public function update(Request $request, $id)
    {
        $invoice = TaxInvoice::find($id);
        if (!$invoice) {
            return response()->json([
                'isOk' => false,
                'error' => 'Bill not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'date'             => 'required|date',
            'customer_name'    => 'nullable|string|max:255|regex:/^[A-Za-z][A-Za-z\s\.\'\-]*$/',
            'phone'            => 'nullable|regex:/^[0-9]{10}$/',
            'vehicle'          => 'nullable|string|max:50',
            'payment_method'   => 'required|in:Khalti,Cash,Credit',
            'transaction_no'   => 'nullable|string|max:255|required_if:payment_method,Khalti',
            'items_json'       => 'required|string',
            'subtotal'         => 'required|numeric|min:0',
            'gst'              => 'required|numeric|min:0',
            'total'            => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'isOk' => false,
                'error' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        try {
            $newItems = $this->parseItems($validated['items_json']);
            if (count($newItems) === 0) {
                return response()->json([
                    'isOk' => false,
                    'error' => 'At least one invoice item is required.',
                ], 422);
            }

            DB::transaction(function () use ($invoice, $validated, $newItems) {
                $oldItems = $this->parseItems((string) $invoice->items_json);
                $this->adjustInventory($oldItems, 1, false);
                $this->adjustInventory($newItems, -1, true);
                $invoice->update($validated);
            });
        } catch (\Throwable $e) {
            return response()->json([
                'isOk' => false,
                'error' => $e->getMessage() ?: 'Unable to update bill right now. Please try again.',
            ], 500);
        }

        return response()->json([
            'isOk' => true,
            'data' => array_merge($invoice->toArray(), ['__backendId' => $invoice->id])
        ]);
    }

    /**
     * Delete invoice
     */
    public function destroy($id)
    {
        $invoice = TaxInvoice::find($id);
        if (!$invoice) {
            return response()->json([
                'isOk' => false,
                'error' => 'Bill not found.',
            ], 404);
        }

        try {
            DB::transaction(function () use ($invoice) {
                $oldItems = $this->parseItems((string) $invoice->items_json);
                $this->adjustInventory($oldItems, 1, false);
                $invoice->delete();
            });
        } catch (\Throwable $e) {
            return response()->json([
                'isOk' => false,
                'error' => $e->getMessage() ?: 'Unable to delete bill right now. Please try again.',
            ], 500);
        }

        return response()->json(['isOk' => true]);
    }
}
