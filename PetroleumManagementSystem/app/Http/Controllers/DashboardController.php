<?php

namespace App\Http\Controllers;

use App\Models\CashRecord;
use App\Models\Expense;
use App\Models\NozzleEntry;
use App\Models\Stock;
use App\Models\TaxInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
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

    private function nozzleLitersForDate(string $date): array
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

        $entry = NozzleEntry::query()
            ->selectRaw("SUM($dieselExpr) as diesel_liters")
            ->selectRaw("SUM($petrolExpr) as petrol_liters")
            ->whereDate('date', $date)
            ->first();

        return [
            'diesel' => round(max(0, (float) ($entry->diesel_liters ?? 0)), 2),
            'petrol' => round(max(0, (float) ($entry->petrol_liters ?? 0)), 2),
        ];
    }

    private function dashboardMetricsForDate(string $date): array
    {
        $dieselStock = Stock::where('fuel_code', 'HSD')->first();
        $petrolStock = Stock::where('fuel_code', 'MS')->first();

        $dieselRate = $this->fuelRate($dieselStock);
        $petrolRate = $this->fuelRate($petrolStock);

        $liters = $this->nozzleLitersForDate($date);
        $dieselSales = round($liters['diesel'] * $dieselRate, 2);
        $petrolSales = round($liters['petrol'] * $petrolRate, 2);
        $nozzleSales = round($dieselSales + $petrolSales, 2);

        $cashFromDenomination = (float) CashRecord::where('date', $date)->sum('total_cash');
        $cashPayment = (float) Expense::whereDate('date', $date)
            ->where('transaction_type', 'payment')
            ->sum('amount');
        $cashExpense = (float) Expense::whereDate('date', $date)
            ->where('transaction_type', 'expense')
            ->sum('amount');
        $cashDeposit = (float) Expense::whereDate('date', $date)
            ->where('transaction_type', 'deposit')
            ->sum('amount');
        $creditSales = (float) TaxInvoice::whereDate('date', $date)
            ->whereRaw('LOWER(payment_method) = ?', ['credit'])
            ->sum('total');
        $invoiceSales = (float) TaxInvoice::whereDate('date', $date)->sum('total');

        $bankAmount = $cashDeposit;

        $netCashSales = round($nozzleSales - $cashDeposit - $cashExpense - $cashPayment - $creditSales, 2);

        // Use the higher source for daily sales to avoid replacing full nozzle sales
        // with partial invoice entries while still honoring invoice-only days.
        $totalSales = round(max($nozzleSales, $invoiceSales), 2);

        return [
            'diesel_liters' => $liters['diesel'],
            'petrol_liters' => $liters['petrol'],
            'diesel_rate' => $dieselRate,
            'petrol_rate' => $petrolRate,
            'diesel_sales' => $dieselSales,
            'petrol_sales' => $petrolSales,
            'total_sales' => $totalSales,
            'total_cash_sales' => $netCashSales,
            'total_credit_sales' => round($creditSales, 2),
            'expenses' => round($cashPayment + $cashExpense, 2),
            'bank_amount' => round($bankAmount, 2),
        ];
    }

    private function fuelKind(?Stock $stock): string
    {
        if (!$stock) {
            return 'other';
        }

        $type = strtolower((string) $stock->fuel_type);
        $code = strtoupper((string) $stock->fuel_code);

        // Prefer fuel_type when available; use code only as fallback.
        if (str_contains($type, 'petrol')) {
            return 'petrol';
        }
        if (str_contains($type, 'diesel')) {
            return 'diesel';
        }
        if (str_contains($type, 'lub')) {
            return 'lubricant';
        }

        if ($code === 'MS') {
            return 'petrol';
        }
        if ($code === 'HSD') {
            return 'diesel';
        }
        if ($code === 'LUB') {
            return 'lubricant';
        }

        return 'other';
    }

    public function index(Request $request)
    {
        $baseNow = now('Asia/Kathmandu');
        $today = $baseNow->toDateString();
        $startDate = $baseNow->copy()->subDays(6)->toDateString();

        $todayMetrics = $this->dashboardMetricsForDate($today);

        $salesChartLabels = [];
        $salesChartSeries = [];
        $expenseChartSeries = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $baseNow->copy()->subDays($i)->toDateString();
            $dailyMetrics = $this->dashboardMetricsForDate($date);
            $salesChartLabels[] = $baseNow->copy()->subDays($i)->format('D');
            $salesChartSeries[] = $dailyMetrics['total_sales'];
            $expenseChartSeries[] = $dailyMetrics['expenses'];
        }

        $stocks = Stock::query()->get();
        $petrolStock = (float) $stocks->filter(fn ($s) => $this->fuelKind($s) === 'petrol')->sum('current_quantity');
        $dieselStock = (float) $stocks->filter(fn ($s) => $this->fuelKind($s) === 'diesel')->sum('current_quantity');
        $lubricantStock = (float) $stocks->filter(fn ($s) => $this->fuelKind($s) === 'lubricant')->sum('current_quantity');

        $recentTaxInvoices = TaxInvoice::query()
            ->latest('date')
            ->latest('id')
            ->limit(8)
            ->get();

        return view('dashboard', [
            'salesTrend' => '+0%',
            'totalSales' => $todayMetrics['total_sales'],
            'cashSalesTrend' => '+0%',
            'totalCashSales' => $todayMetrics['total_cash_sales'],
            'creditSalesTrend' => '+0%',
            'totalCreditSales' => $todayMetrics['total_credit_sales'],
            'expenseTrend' => '-0%',
            'expenses' => $todayMetrics['expenses'],
            'bankTrend' => '+0%',
            'bankDeposit' => $todayMetrics['bank_amount'],
            'dieselLiters' => $todayMetrics['diesel_liters'],
            'petrolLiters' => $todayMetrics['petrol_liters'],
            'dieselRate' => $todayMetrics['diesel_rate'],
            'petrolRate' => $todayMetrics['petrol_rate'],
            'petrolStock' => $petrolStock,
            'dieselStock' => $dieselStock,
            'lubricantStock' => $lubricantStock,
            'salesChartLabels' => $salesChartLabels,
            'salesChartSeries' => $salesChartSeries,
            'expenseChartSeries' => $expenseChartSeries,
            'recentTaxInvoices' => $recentTaxInvoices,
        ]);
    }

    public function getSalesData()
    {
        return response()->json(['data' => []]);
    }

    public function getStats()
    {
        return response()->json(['stats' => []]);
    }

    // Resource stubs used by routes
    public function stock() { return view('stock'); }
    public function storeStock(Request $r) { return response('store stock'); }
    public function updateStock($id) { return response("update stock {$id}"); }
    public function deleteStock($id) { return response("delete stock {$id}"); }

    public function inventory() { return view('inventory'); }
    public function storeInventory(Request $r) { return response('store inventory'); }
    public function updateInventory($id) { return response("update inventory {$id}"); }
    public function deleteInventory($id) { return response("delete inventory {$id}"); }

    public function nozzle() { return view('nozzle'); }
    public function storeNozzle(Request $r) { return response('store nozzle'); }
    public function updateNozzle($id) { return response("update nozzle {$id}"); }
    public function deleteNozzle($id) { return response("delete nozzle {$id}"); }

    public function taxinvoice() { return view('taxinvoice'); }

    public function expenses() { return view('expenses'); }
    public function storeExpense(Request $r) { return response('store expense'); }
    public function updateExpense($id) { return response("update expense {$id}"); }
    public function deleteExpense($id) { return response("delete expense {$id}"); }

    public function reports() { return view('reports'); }
    public function exportReports() { return response('export reports'); }

    public function cash() { return view('cash'); }
    public function storeCash(Request $r) { return response('store cash'); }
    public function updateCash($id) { return response("update cash {$id}"); }
    public function deleteCash($id) { return response("delete cash {$id}"); }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[A-Za-z][A-Za-z\s\.\'\-]*$/',
            'phoneNumber' => 'nullable|regex:/^[0-9]{10}$/',
            'address' => 'nullable|string|max:500'
        ]);

        auth()->user()->update($validated);

        return redirect()->route('admin.index')->with('success', 'Profile updated successfully!');
    }

    public function employeePortal()
    {
        return view('employee');
    }

    public function updateEmployeeProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[A-Za-z][A-Za-z\s\.\'\-]*$/',
            'phoneNumber' => 'nullable|regex:/^[0-9]{10}$/',
            'address' => 'nullable|string|max:500',
            'role' => 'required|in:employee',
        ]);

        auth()->user()->update($validated);

        return redirect()->route('employee.index')->with('success', 'Employee profile updated successfully!');
    }
}
