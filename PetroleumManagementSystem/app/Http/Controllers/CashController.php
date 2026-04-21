<?php

namespace App\Http\Controllers;

use App\Models\CashRecord;
use App\Models\Expense;
use App\Models\NozzleEntry;
use App\Models\Stock;
use App\Models\TaxInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CashController extends Controller
{
    public function index()
    {
        $salesSummary = $this->todaySalesSummary();

        return view('cash', [
            'defaultTotalSales' => $salesSummary['adjusted_total_sales'],
            'nozzleSales' => $salesSummary['nozzle_sales'],
            'cashReceive' => $salesSummary['cash_receive'],
            'cashPayment' => $salesSummary['cash_payment'],
            'cashExpense' => $salesSummary['cash_expense'],
            'cashDeposit' => $salesSummary['cash_deposit'],
        ]);
    }

    private function todaySalesSummary(): array
    {
        $today = now('Asia/Kathmandu')->toDateString();
        $hasNozzle34 = Schema::hasColumns('nozzle_entries', [
            'diesel_n3_opening',
            'diesel_n3_closing',
            'diesel_n4_opening',
            'diesel_n4_closing',
            'petrol_n3_opening',
            'petrol_n3_closing',
            'petrol_n4_opening',
            'petrol_n4_closing',
        ]);

        $dieselExpr = '(diesel_n1_closing - diesel_n1_opening) + (diesel_n2_closing - diesel_n2_opening)';
        $petrolExpr = '(petrol_n1_closing - petrol_n1_opening) + (petrol_n2_closing - petrol_n2_opening)';

        if ($hasNozzle34) {
            $dieselExpr .= ' + (diesel_n3_closing - diesel_n3_opening) + (diesel_n4_closing - diesel_n4_opening)';
            $petrolExpr .= ' + (petrol_n3_closing - petrol_n3_opening) + (petrol_n4_closing - petrol_n4_opening)';
        }

        $entry = NozzleEntry::query()
            ->selectRaw("SUM($dieselExpr) as diesel_liters")
            ->selectRaw("SUM($petrolExpr) as petrol_liters")
            ->whereDate('date', $today)
            ->first();

        $dieselLiters = round(max(0, (float) ($entry->diesel_liters ?? 0)), 2);
        $petrolLiters = round(max(0, (float) ($entry->petrol_liters ?? 0)), 2);

        $dieselRate = (float) (Stock::where('fuel_code', 'HSD')->value('selling_price')
            ?: Stock::where('fuel_code', 'HSD')->value('unit_price')
            ?: 0);
        $petrolRate = (float) (Stock::where('fuel_code', 'MS')->value('selling_price')
            ?: Stock::where('fuel_code', 'MS')->value('unit_price')
            ?: 0);

        $nozzleSales = round(($dieselLiters * $dieselRate) + ($petrolLiters * $petrolRate), 2);

        $cashReceive = round((float) Expense::whereDate('date', $today)
            ->where('transaction_type', 'receive')
            ->sum('amount'), 2);
        $cashPayment = round((float) Expense::whereDate('date', $today)
            ->where('transaction_type', 'payment')
            ->sum('amount'), 2);
        $cashExpense = round((float) Expense::whereDate('date', $today)
            ->where('transaction_type', 'expense')
            ->sum('amount'), 2);
        $cashDeposit = round((float) Expense::whereDate('date', $today)
            ->where('transaction_type', 'deposit')
            ->sum('amount'), 2);

        $creditSales = round((float) TaxInvoice::whereDate('date', $today)
            ->whereRaw('LOWER(payment_method) = ?', ['credit'])
            ->sum('total'), 2);

        // Total Cash Sales = Total Sales - Deposit - Expense - Payment - Credit.
        $adjustedTotalSales = round($nozzleSales - $cashDeposit - $cashExpense - $cashPayment - $creditSales, 2);

        return [
            'nozzle_sales' => $nozzleSales,
            'cash_receive' => $cashReceive,
            'cash_payment' => $cashPayment,
            'cash_expense' => $cashExpense,
            'cash_deposit' => $cashDeposit,
            'credit_sales' => $creditSales,
            'adjusted_total_sales' => $adjustedTotalSales,
        ];
    }

    public function getAll()
    {
        $records = CashRecord::orderBy('created_at', 'desc')->get()
            ->map(fn($r) => array_merge($r->toArray(), ['__backendId' => $r->id]));

        return response()->json(['isOk' => true, 'data' => $records]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedPayload($request);

        $record = CashRecord::create($data);

        return response()->json(['isOk' => true, 'data' => array_merge($record->toArray(), ['__backendId' => $record->id])]);
    }

    public function update(Request $request, $id)
    {
        $record = CashRecord::findOrFail($id);
        $data = $this->validatedPayload($request);
        $record->update($data);

        return response()->json(['isOk' => true, 'data' => array_merge($record->fresh()->toArray(), ['__backendId' => $record->id])]);
    }

    private function validatedPayload(Request $request): array
    {
        $validated = $request->validate([
            'date'          => 'nullable|string|max:50',
            'qty_1000'      => 'nullable|integer|min:0',
            'qty_500'       => 'nullable|integer|min:0',
            'qty_100'       => 'nullable|integer|min:0',
            'qty_50'        => 'nullable|integer|min:0',
            'qty_20'        => 'nullable|integer|min:0',
            'qty_10'        => 'nullable|integer|min:0',
            'qty_5'         => 'nullable|integer|min:0',
            'cheque_amount' => 'nullable|numeric|min:0',
            'ic_amount'     => 'nullable|numeric|min:0',
            'total_sales'   => 'nullable|numeric|min:0',
        ]);

        $qty1000 = (int) ($validated['qty_1000'] ?? 0);
        $qty500 = (int) ($validated['qty_500'] ?? 0);
        $qty100 = (int) ($validated['qty_100'] ?? 0);
        $qty50 = (int) ($validated['qty_50'] ?? 0);
        $qty20 = (int) ($validated['qty_20'] ?? 0);
        $qty10 = (int) ($validated['qty_10'] ?? 0);
        $qty5 = (int) ($validated['qty_5'] ?? 0);

        $totalCash = ($qty1000 * 1000)
            + ($qty500 * 500)
            + ($qty100 * 100)
            + ($qty50 * 50)
            + ($qty20 * 20)
            + ($qty10 * 10)
            + ($qty5 * 5);

        $chequeAmount = (float) ($validated['cheque_amount'] ?? 0);
        $icAmount = (float) ($validated['ic_amount'] ?? 0);
        $totalSales = (float) ($validated['total_sales'] ?? 0);
        $netCash = $totalCash + $chequeAmount + $icAmount;
        $difference = $netCash - $totalSales;

        $recordDate = now()->format('Y-m-d');
        if (isset($validated['date']) && $validated['date'] !== '') {
            try {
                $recordDate = Carbon::parse($validated['date'])->format('Y-m-d');
            } catch (\Throwable $e) {
                $recordDate = now()->format('Y-m-d');
            }
        }

        return [
            'date' => $recordDate,
            'qty_1000' => $qty1000,
            'qty_500' => $qty500,
            'qty_100' => $qty100,
            'qty_50' => $qty50,
            'qty_20' => $qty20,
            'qty_10' => $qty10,
            'qty_5' => $qty5,
            'total_cash' => $totalCash,
            'cheque_amount' => round($chequeAmount, 2),
            'net_cash' => round($netCash, 2),
            'ic_amount' => round($icAmount, 2),
            'total_sales' => round($totalSales, 2),
            'difference' => round($difference, 2),
        ];
    }

    public function destroy($id)
    {
        $record = CashRecord::findOrFail($id);
        $record->delete();

        return response()->json(['isOk' => true]);
    }
}
