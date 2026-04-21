<?php

namespace App\Http\Controllers;

use App\Models\CashRecord;
use App\Models\Expense;
use App\Models\NozzleEntry;
use App\Models\Stock;
use App\Models\TaxInvoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Schema;

class ReportsController extends Controller
{
    private function fuelRate(?Stock $stock): float
    {
        if (!$stock) {
            return 0.0;
        }

        $selling = (float) ($stock->selling_price ?? 0);
        if ($selling > 0) {
            return $selling;
        }

        return (float) ($stock->unit_price ?? 0);
    }

    private function getSalesSummary(string $fromDate, string $toDate): array
    {
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

        $nozzle = NozzleEntry::query()
            ->selectRaw("SUM($dieselExpr) as diesel_liters")
            ->selectRaw("SUM($petrolExpr) as petrol_liters")
            ->whereBetween('date', [$fromDate, $toDate])
            ->first();

        $dieselLiters = round(max(0, (float) ($nozzle->diesel_liters ?? 0)), 2);
        $petrolLiters = round(max(0, (float) ($nozzle->petrol_liters ?? 0)), 2);

        $dieselRate = $this->fuelRate(Stock::where('fuel_code', 'HSD')->first());
        $petrolRate = $this->fuelRate(Stock::where('fuel_code', 'MS')->first());

        $dieselSales = round($dieselLiters * $dieselRate, 2);
        $petrolSales = round($petrolLiters * $petrolRate, 2);
        $totalSales = round($dieselSales + $petrolSales, 2);

        $cashFromDenomination = round((float) CashRecord::query()
            ->whereBetween('date', [$fromDate, $toDate])
            ->sum('total_cash'), 2);

        $cashReceive = round((float) Expense::query()
            ->whereBetween('date', [$fromDate, $toDate])
            ->where('transaction_type', 'receive')
            ->sum('amount'), 2);

        $cashPayment = round((float) Expense::query()
            ->whereBetween('date', [$fromDate, $toDate])
            ->where('transaction_type', 'payment')
            ->sum('amount'), 2);

        $cashExpense = round((float) Expense::query()
            ->whereBetween('date', [$fromDate, $toDate])
            ->where('transaction_type', 'expense')
            ->sum('amount'), 2);

        $cashDeposit = round((float) Expense::query()
            ->whereBetween('date', [$fromDate, $toDate])
            ->where('transaction_type', 'deposit')
            ->sum('amount'), 2);

        $cashSales = round($cashFromDenomination + $cashReceive - $cashPayment - $cashExpense - $cashDeposit, 2);

        $creditSales = round((float) TaxInvoice::query()
            ->whereBetween('date', [$fromDate, $toDate])
            ->whereRaw('LOWER(payment_method) = ?', ['credit'])
            ->sum('total'), 2);

        $bankFromCash = round((float) CashRecord::query()
            ->whereBetween('date', [$fromDate, $toDate])
            ->sum('net_cash'), 2);

        $bankFromDeposit = $cashDeposit;

        return [
            'diesel_liters' => $dieselLiters,
            'petrol_liters' => $petrolLiters,
            'diesel_rate' => round($dieselRate, 2),
            'petrol_rate' => round($petrolRate, 2),
            'diesel_sales' => $dieselSales,
            'petrol_sales' => $petrolSales,
            'total_sales' => $totalSales,
            'total_cash_sales' => $cashSales,
            'total_credit_sales' => $creditSales,
            'expenses' => round($cashPayment + $cashExpense, 2),
            'in_bank' => round($bankFromCash + $bankFromDeposit, 2),
        ];
    }

    /**
     * Show reports page
     */
    public function index()
    {
        return view('reports');
    }

    /**
     * Generate report data
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:sales,expenses,inventory',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $reportType = $validated['report_type'];
        $fromDate = $validated['from_date'];
        $toDate = $validated['to_date'];

        $data = [];
        $summary = [];

        switch ($reportType) {
            case 'sales':
                $data = $this->getSalesReport($fromDate, $toDate);
                $summary = $this->getSalesSummary($fromDate, $toDate);
                break;
            case 'expenses':
                $data = $this->getExpensesReport($fromDate, $toDate);
                break;
            case 'inventory':
                $data = $this->getInventoryReport($fromDate, $toDate);
                break;
        }

        return response()->json([
            'success' => true,
            'report_type' => $reportType,
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'data' => $data,
            'summary' => $summary,
        ]);
    }

    /**
     * Get sales report data
     */
    private function getSalesReport($fromDate, $toDate)
    {
        $entries = TaxInvoice::whereBetween('date', [$fromDate, $toDate])
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($entry) {
                $items = json_decode($entry->items_json ?? '[]', true);
                if (!is_array($items)) {
                    $items = [];
                }

                $totalQty = collect($items)->sum(function ($item) {
                    return (float) ($item['qty'] ?? 0);
                });

                return [
                    'date'          => $entry->date,
                    'bill_number'   => $entry->bill_number,
                    'customer_name' => $entry->customer_name,
                    'phone'         => $entry->phone,
                    'vehicle'       => $entry->vehicle,
                    'payment_method'=> $entry->payment_method,
                    'transaction_no'=> $entry->transaction_no,
                    'item_count'    => count($items),
                    'total_qty'     => $totalQty,
                    'subtotal'      => (float) $entry->subtotal,
                    'vat'           => (float) $entry->gst,
                    'total'         => (float) $entry->total,
                ];
            });

        return $entries;
    }

    /**
     * Get expenses report data
     */
    private function getExpensesReport($fromDate, $toDate)
    {
        $expenses = Expense::whereBetween('date', [$fromDate, $toDate])
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($expense) {
                return [
                    'date' => $expense->date,
                    'type' => $expense->transaction_type,
                    'vendor' => $expense->vendor_name,
                    'category' => $expense->category,
                    'description' => $expense->description,
                    'amount' => $expense->amount,
                    'payment_method' => $expense->payment_method,
                ];
            });

        return $expenses;
    }

    /**
     * Get inventory report data
     */
    private function getInventoryReport($fromDate, $toDate)
    {
        $inventory = Stock::orderBy('created_at', 'desc')
            ->get()
            ->map(function ($stock) {
                return [
                    'date'            => $stock->updated_at->format('Y-m-d'),
                    'product'         => $stock->fuel_type,
                    'sku'             => $stock->fuel_code,
                    'current_stock'   => $stock->current_quantity,
                    'unit_price'      => $stock->unit_price,
                    'total_value'     => $stock->total_value,
                ];
            });

        return $inventory;
    }

    /**
     * Generate PDF report
     */
    public function generatePDF(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:sales,expenses,inventory',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ]);

        $reportType = $validated['report_type'];
        $fromDate = $validated['from_date'];
        $toDate = $validated['to_date'];

        $data = [];
        $summary = [];

        switch ($reportType) {
            case 'sales':
                $data = $this->getSalesReport($fromDate, $toDate);
                $summary = $this->getSalesSummary($fromDate, $toDate);
                break;
            case 'expenses':
                $data = $this->getExpensesReport($fromDate, $toDate);
                break;
            case 'inventory':
                $data = $this->getInventoryReport($fromDate, $toDate);
                break;
        }

        $pdf = Pdf::loadView('reports.pdf', [
            'report_type' => $reportType,
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'data' => $data,
            'summary' => $summary,
        ]);

        return $pdf->download(ucfirst($reportType) . '_Report_' . $fromDate . '_to_' . $toDate . '.pdf');
    }

}
