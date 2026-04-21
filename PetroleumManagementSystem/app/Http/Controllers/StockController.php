<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\StockVoucher;
use App\Models\NozzleEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class StockController extends Controller
{
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

    public function index()
    {
        $stocks = Stock::all();
        $nextStockVoucherNumber = 'V' . (((int) (StockVoucher::max('id') ?? 0)) + 1001);

        $petrol = $stocks->first(fn ($stock) => $this->fuelKind($stock) === 'petrol');
        $diesel = $stocks->first(fn ($stock) => $this->fuelKind($stock) === 'diesel');

        $totalValue = $stocks->sum('total_value');

        $cardMeta = [
            'MS' => ['icon' => '⛽', 'title' => 'Petrol (MS)', 'color' => '#667eea'],
            'HSD' => ['icon' => '🛢️', 'title' => 'Diesel (HSD)', 'color' => '#10b981'],
            'LUB' => ['icon' => '🫧', 'title' => 'Lubricants', 'color' => '#f59e0b'],
        ];

        return view('stock', compact('stocks', 'petrol', 'diesel', 'totalValue', 'cardMeta', 'nextStockVoucherNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fuel_type' => 'required',
            'quantity' => 'required|numeric',
            'price_per_liter' => 'required|numeric',
        ]);

        Stock::create([
            'fuel_type' => $request->fuel_type,
            'current_quantity' => $request->quantity,
            'unit_price' => $request->price_per_liter,
            'total_value' => $request->quantity * $request->price_per_liter,
        ]);

        return back()->with('success', 'Stock saved successfully');
    }

    public function reductionStats(Request $request, string $fuelCode)
    {
        $fuelCode = strtoupper($fuelCode);
        if (!in_array($fuelCode, ['MS', 'HSD'], true)) {
            return response()->json([
                'isOk' => false,
                'error' => 'Reduction analytics is available for MS and HSD only.',
            ], 422);
        }

        $period = strtolower($request->query('period', 'day'));
        if (!in_array($period, ['day', 'week', 'month', 'year'], true)) {
            $period = 'day';
        }

        $anchorDate = $request->query('date')
            ? Carbon::parse($request->query('date'))->startOfDay()
            : now()->startOfDay();

        if ($period === 'day') {
            $start = $anchorDate->copy();
            $end = $anchorDate->copy();
        } elseif ($period === 'week') {
            $start = $anchorDate->copy()->subDays(6);
            $end = $anchorDate->copy();
        } elseif ($period === 'month') {
            $start = $anchorDate->copy()->startOfMonth();
            $end = $anchorDate->copy();
        } else {
            $start = $anchorDate->copy()->startOfYear();
            $end = $anchorDate->copy();
        }

        $entries = NozzleEntry::query()
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date')
            ->get();

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

        $rows = $entries->map(function ($entry) use ($fuelCode, $hasNozzle34) {
            if ($fuelCode === 'MS') {
                $reduced = ((float) $entry->petrol_n1_closing - (float) $entry->petrol_n1_opening)
                    + ((float) $entry->petrol_n2_closing - (float) $entry->petrol_n2_opening);

                if ($hasNozzle34) {
                    $reduced += ((float) $entry->petrol_n3_closing - (float) $entry->petrol_n3_opening)
                        + ((float) $entry->petrol_n4_closing - (float) $entry->petrol_n4_opening);
                }
            } else {
                $reduced = ((float) $entry->diesel_n1_closing - (float) $entry->diesel_n1_opening)
                    + ((float) $entry->diesel_n2_closing - (float) $entry->diesel_n2_opening);

                if ($hasNozzle34) {
                    $reduced += ((float) $entry->diesel_n3_closing - (float) $entry->diesel_n3_opening)
                        + ((float) $entry->diesel_n4_closing - (float) $entry->diesel_n4_opening);
                }
            }

            return [
                'date' => $entry->date,
                'reduced_liters' => round(max(0, $reduced), 2),
            ];
        })->groupBy('date')->map(function ($group, $date) {
            return [
                'date' => $date,
                'reduced_liters' => round($group->sum('reduced_liters'), 2),
            ];
        })->values();

        $totalReduced = round($rows->sum('reduced_liters'), 2);
        $stock = Stock::where('fuel_code', $fuelCode)->first();

        return response()->json([
            'isOk' => true,
            'data' => [
                'fuel_code' => $fuelCode,
                'period' => $period,
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'total_reduced_liters' => $totalReduced,
                'current_stock_liters' => $stock ? (float) $stock->current_quantity : 0,
                'rows' => $rows,
            ],
        ]);
    }
}
