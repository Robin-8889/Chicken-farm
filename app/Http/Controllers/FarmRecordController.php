<?php

namespace App\Http\Controllers;

use App\Models\ChickenBatch;
use App\Models\EggRecord;
use App\Models\Expense;
use App\Models\FarmStock;
use App\Models\GrowthRecord;
use App\Models\MortalityRecord;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FarmRecordController extends Controller
{
    public function storeBatch(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'date_of_arrival' => ['required', 'date'],
            'chicken_type' => ['required', 'string', 'max:100'],
            'breed_name' => ['required', 'string', 'max:150'],
            'number_entered' => ['required', 'integer', 'min:1'],
            'initial_average_weight_kg' => ['required', 'numeric', 'min:0'],
            'supplier_source' => ['nullable', 'string', 'max:255'],
            'purchase_cost' => ['required', 'numeric', 'min:0'],
            'expected_purpose' => ['required', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:50'],
            'next_vaccination_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        ChickenBatch::create($data + [
            'batch_id' => $this->generateBatchId(),
        ]);

        return back()->with('status', 'Batch saved successfully.');
    }

    public function storeGrowth(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'chicken_batch_id' => ['required', 'exists:chicken_batches,id'],
            'week_number' => ['required', 'integer', 'min:1'],
            'average_weight_kg' => ['required', 'numeric', 'min:0'],
            'feed_consumed_kg' => ['required', 'numeric', 'min:0'],
            'health_status' => ['required', 'string', 'max:100'],
            'mortality_recorded' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        GrowthRecord::updateOrCreate(
            [
                'chicken_batch_id' => $data['chicken_batch_id'],
                'week_number' => $data['week_number'],
            ],
            $data
        );

        return back()->with('status', 'Growth record saved successfully.');
    }

    public function storeEgg(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'chicken_batch_id' => ['required', 'exists:chicken_batches,id'],
            'record_date' => ['required', 'date'],
            'total_eggs_produced' => ['required', 'integer', 'min:0'],
            'broken_eggs' => ['required', 'integer', 'min:0'],
            'eggs_consumed_home' => ['required', 'integer', 'min:0'],
            'eggs_sold' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $remainingEggs = max(0, $data['total_eggs_produced'] - $data['broken_eggs'] - $data['eggs_consumed_home'] - $data['eggs_sold']);

        EggRecord::updateOrCreate(
            [
                'chicken_batch_id' => $data['chicken_batch_id'],
                'record_date' => $data['record_date'],
            ],
            $data + [
                'remaining_eggs' => $remainingEggs,
            ]
        );

        return back()->with('status', 'Egg production record saved successfully.');
    }

    public function storeStock(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'chicken_batch_id' => ['nullable', 'exists:chicken_batches,id'],
            'category' => ['required', 'string', 'max:50'],
            'item_name' => ['required', 'string', 'max:150'],
            'quantity_purchased' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'quantity_used' => ['required', 'numeric', 'min:0'],
            'remaining_quantity' => ['required', 'numeric', 'min:0'],
            'purchase_date' => ['required', 'date'],
            'expiry_date' => ['nullable', 'date'],
            'cost' => ['required', 'numeric', 'min:0'],
            'low_stock_threshold' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        FarmStock::create($data);

        return back()->with('status', 'Stock record saved successfully.');
    }

    public function useStock(Request $request, FarmStock $stock): RedirectResponse
    {
        $data = $request->validate([
            'used_quantity' => ['required', 'numeric', 'min:0.01'],
            'usage_notes' => ['nullable', 'string', 'max:255'],
        ]);

        $usedQuantity = (float) $data['used_quantity'];
        $remainingQuantity = (float) $stock->remaining_quantity;

        if ($usedQuantity > $remainingQuantity) {
            return back()->withErrors([
                'used_quantity' => 'Used quantity cannot be greater than the current remaining stock.',
            ]);
        }

        $stock->quantity_used = (float) $stock->quantity_used + $usedQuantity;
        $stock->remaining_quantity = $remainingQuantity - $usedQuantity;

        if (!empty($data['usage_notes'])) {
            $existingNotes = trim((string) $stock->notes);
            $usageNote = 'Usage: ' . $data['usage_notes'];
            $stock->notes = $existingNotes === '' ? $usageNote : $existingNotes . "\n" . $usageNote;
        }

        $stock->save();

        return back()->with('status', 'Stock usage recorded successfully.');
    }

    public function storeMortality(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'chicken_batch_id' => ['required', 'exists:chicken_batches,id'],
            'record_date' => ['required', 'date'],
            'number_dead' => ['required', 'integer', 'min:0'],
            'cause_of_death' => ['nullable', 'string', 'max:255'],
            'number_consumed_home' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        MortalityRecord::updateOrCreate(
            [
                'chicken_batch_id' => $data['chicken_batch_id'],
                'record_date' => $data['record_date'],
            ],
            $data
        );

        return back()->with('status', 'Mortality record saved successfully.');
    }

    public function storeSale(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'chicken_batch_id' => ['nullable', 'exists:chicken_batches,id'],
            'sale_type' => ['required', 'in:chicken,egg'],
            'number_sold' => ['required', 'integer', 'min:0'],
            'weight_sold_kg' => ['nullable', 'numeric', 'min:0'],
            'price_per_unit' => ['required', 'numeric', 'min:0'],
            'buyer_name' => ['nullable', 'string', 'max:255'],
            'buyer_contact' => ['nullable', 'string', 'max:255'],
            'sale_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        Sale::create($data + [
            'total_revenue' => $data['number_sold'] * $data['price_per_unit'],
        ]);

        return back()->with('status', 'Sale record saved successfully.');
    }

    public function storeExpense(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'chicken_batch_id' => ['nullable', 'exists:chicken_batches,id'],
            'expense_date' => ['required', 'date'],
            'category' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        Expense::create($data);

        return back()->with('status', 'Expense recorded successfully.');
    }

    public function updateUserRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', 'in:admin,worker'],
        ]);

        $user->update($data);

        return back()->with('status', 'User role updated successfully.');
    }

    protected function generateBatchId(): string
    {
        $sequence = ChickenBatch::query()->count() + 1;

        return 'BATCH-' . now()->format('Y') . '-' . Str::padLeft((string) $sequence, 3, '0');
    }
}