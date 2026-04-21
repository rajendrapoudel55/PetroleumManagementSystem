<?php

namespace App\Http\Controllers;

use App\Models\NozzleEntry;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NozzleEntryController extends Controller
{
    // Show nozzle page
    public function index()
    {
        $entries = NozzleEntry::latest()->get();
        return view('nozzle', compact('entries')); // 👈 FILE NAME MATCHES
    }

    public function store(Request $request)
    {
        $validated = $this->validateEntry($request);
        $sold = $this->calculateSoldLiters($validated);

        DB::transaction(function () use ($validated, $sold) {
            $this->applyStockDelta(-$sold['petrol'], -$sold['diesel']);
            NozzleEntry::create($validated);
        });

        return response()->json([
            'success' => true,
            'message' => 'Nozzle entry saved and stock updated successfully',
            'sold' => $sold,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateEntry($request);
        $entry = NozzleEntry::findOrFail($id);

        $oldSold = $this->calculateSoldLiters($entry->toArray());
        $newSold = $this->calculateSoldLiters($validated);

        DB::transaction(function () use ($entry, $validated, $oldSold, $newSold) {
            // Revert previous consumption, then apply updated consumption.
            $petrolDelta = $oldSold['petrol'] - $newSold['petrol'];
            $dieselDelta = $oldSold['diesel'] - $newSold['diesel'];

            $this->applyStockDelta($petrolDelta, $dieselDelta);
            $entry->update($validated);
        });

        return response()->json([
            'success' => true,
            'message' => 'Nozzle entry updated and stock synced successfully',
            'sold' => $newSold,
        ], 200);
    }

    public function destroy($id)
    {
        $entry = NozzleEntry::findOrFail($id);

        $sold = $this->calculateSoldLiters($entry->toArray());

        DB::transaction(function () use ($entry, $sold) {
            // Restore stock when an entry is removed.
            $this->applyStockDelta($sold['petrol'], $sold['diesel']);
            $entry->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Entry deleted and stock restored successfully',
        ], 200);
    }

    private function validateEntry(Request $request): array
    {
        $rules = [
            'date' => 'required|date',

            'diesel_n1_opening' => 'required|numeric|min:0',
            'diesel_n1_closing' => 'required|numeric|gte:diesel_n1_opening',

            'diesel_n2_opening' => 'required|numeric|min:0',
            'diesel_n2_closing' => 'required|numeric|gte:diesel_n2_opening',

            'petrol_n1_opening' => 'required|numeric|min:0',
            'petrol_n1_closing' => 'required|numeric|gte:petrol_n1_opening',

            'petrol_n2_opening' => 'required|numeric|min:0',
            'petrol_n2_closing' => 'required|numeric|gte:petrol_n2_opening',
        ];

        if ($this->hasNozzle34Columns()) {
            $rules['diesel_n3_opening'] = 'required|numeric|min:0';
            $rules['diesel_n3_closing'] = 'required|numeric|gte:diesel_n3_opening';
            $rules['diesel_n4_opening'] = 'required|numeric|min:0';
            $rules['diesel_n4_closing'] = 'required|numeric|gte:diesel_n4_opening';
            $rules['petrol_n3_opening'] = 'required|numeric|min:0';
            $rules['petrol_n3_closing'] = 'required|numeric|gte:petrol_n3_opening';
            $rules['petrol_n4_opening'] = 'required|numeric|min:0';
            $rules['petrol_n4_closing'] = 'required|numeric|gte:petrol_n4_opening';
        }

        $validated = $request->validate($rules);

        // Prevent insert/update with columns that do not exist in current DB schema.
        return array_filter(
            $validated,
            fn ($key) => Schema::hasColumn('nozzle_entries', $key),
            ARRAY_FILTER_USE_KEY
        );
    }

    private function calculateSoldLiters(array $data): array
    {
        $petrol = ((float) $data['petrol_n1_closing'] - (float) $data['petrol_n1_opening'])
            + ((float) $data['petrol_n2_closing'] - (float) $data['petrol_n2_opening'])
            + ((float) ($data['petrol_n3_closing'] ?? 0) - (float) ($data['petrol_n3_opening'] ?? 0))
            + ((float) ($data['petrol_n4_closing'] ?? 0) - (float) ($data['petrol_n4_opening'] ?? 0));

        $diesel = ((float) $data['diesel_n1_closing'] - (float) $data['diesel_n1_opening'])
            + ((float) $data['diesel_n2_closing'] - (float) $data['diesel_n2_opening'])
            + ((float) ($data['diesel_n3_closing'] ?? 0) - (float) ($data['diesel_n3_opening'] ?? 0))
            + ((float) ($data['diesel_n4_closing'] ?? 0) - (float) ($data['diesel_n4_opening'] ?? 0));

        return [
            'petrol' => round(max(0, $petrol), 2),
            'diesel' => round(max(0, $diesel), 2),
        ];
    }

    private function hasNozzle34Columns(): bool
    {
        return Schema::hasColumns('nozzle_entries', [
            'diesel_n3_opening',
            'diesel_n3_closing',
            'diesel_n4_opening',
            'diesel_n4_closing',
            'petrol_n3_opening',
            'petrol_n3_closing',
            'petrol_n4_opening',
            'petrol_n4_closing',
        ]);
    }

    private function applyStockDelta(float $petrolDelta, float $dieselDelta): void
    {
        $petrolStock = Stock::where('fuel_code', 'MS')->lockForUpdate()->first();
        $dieselStock = Stock::where('fuel_code', 'HSD')->lockForUpdate()->first();

        if (!$petrolStock || !$dieselStock) {
            abort(422, 'Petrol/Diesel stock rows are missing. Please seed stocks first.');
        }

        $newPetrolQty = (float) $petrolStock->current_quantity + $petrolDelta;
        $newDieselQty = (float) $dieselStock->current_quantity + $dieselDelta;

        if ($newPetrolQty < 0 || $newDieselQty < 0) {
            abort(422, 'Insufficient stock for this nozzle entry.');
        }

        $petrolStock->current_quantity = round($newPetrolQty, 2);
        $petrolStock->total_value = round($petrolStock->current_quantity * (float) $petrolStock->unit_price, 2);
        $petrolStock->save();

        $dieselStock->current_quantity = round($newDieselQty, 2);
        $dieselStock->total_value = round($dieselStock->current_quantity * (float) $dieselStock->unit_price, 2);
        $dieselStock->save();
    }
}