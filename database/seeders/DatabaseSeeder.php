<?php

namespace Database\Seeders;

use App\Models\ChickenBatch;
use App\Models\EggRecord;
use App\Models\Expense;
use App\Models\FarmStock;
use App\Models\GrowthRecord;
use App\Models\MortalityRecord;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminAttrs = ['name' => 'Farm Owner', 'password' => Hash::make('password')];
        if (Schema::hasColumn('users', 'role')) {
            $adminAttrs['role'] = 'admin';
        }

        User::query()->updateOrCreate(['email' => 'admin@chickenfarm.test'], $adminAttrs);

        $workerAttrs = ['name' => 'Farm Worker', 'password' => Hash::make('password')];
        if (Schema::hasColumn('users', 'role')) {
            $workerAttrs['role'] = 'worker';
        }

        User::query()->updateOrCreate(['email' => 'worker@chickenfarm.test'], $workerAttrs);

        $batch = ChickenBatch::query()->updateOrCreate(
            ['batch_id' => 'BATCH-2026-001'],
            [
                'date_of_arrival' => now()->subWeeks(4)->toDateString(),
                'chicken_type' => 'Broiler',
                'breed_name' => 'Ross 308',
                'number_entered' => 200,
                'initial_average_weight_kg' => 0.04,
                'supplier_source' => 'Green Valley Hatchery',
                'purchase_cost' => 450000,
                'expected_purpose' => 'Meat',
                'status' => 'active',
                'next_vaccination_at' => now()->addDays(7)->toDateString(),
                'notes' => 'Starter demo batch for dashboard values.',
            ]
        );

        GrowthRecord::query()->where('chicken_batch_id', $batch->id)->where('week_number', 1)->delete();
        GrowthRecord::query()->create([
            'chicken_batch_id' => $batch->id,
            'week_number' => 1,
            'average_weight_kg' => 0.18,
            'feed_consumed_kg' => 14,
            'health_status' => 'Good',
            'mortality_recorded' => 2,
            'notes' => 'Healthy start with slight stress during brooding.',
        ]);

        GrowthRecord::query()->where('chicken_batch_id', $batch->id)->where('week_number', 2)->delete();
        GrowthRecord::query()->create([
            'chicken_batch_id' => $batch->id,
            'week_number' => 2,
            'average_weight_kg' => 0.42,
            'feed_consumed_kg' => 28,
            'health_status' => 'Good',
            'mortality_recorded' => 1,
            'notes' => 'Weight growth is on target.',
        ]);

        EggRecord::query()
            ->where('chicken_batch_id', $batch->id)
            ->whereDate('record_date', now()->toDateString())
            ->delete();

        EggRecord::query()->create([
            'chicken_batch_id' => $batch->id,
            'record_date' => now()->toDateString(),
            'total_eggs_produced' => 0,
            'broken_eggs' => 0,
            'eggs_consumed_home' => 0,
            'eggs_sold' => 0,
            'remaining_eggs' => 0,
            'notes' => 'Layer records will start once this batch switches to production.',
        ]);

        FarmStock::query()->updateOrCreate(
            ['category' => 'feed', 'item_name' => 'Starter Mash', 'purchase_date' => now()->subWeeks(2)->toDateString()],
            [
                'chicken_batch_id' => $batch->id,
                'quantity_purchased' => 40,
                'unit' => 'bags',
                'quantity_used' => 12,
                'remaining_quantity' => 28,
                'expiry_date' => null,
                'cost' => 320000,
                'low_stock_threshold' => 10,
                'notes' => 'Primary feed for the active batch.',
            ]
        );

        FarmStock::query()->updateOrCreate(
            ['category' => 'medicine', 'item_name' => 'Newcastle Vaccine', 'purchase_date' => now()->subWeeks(1)->toDateString()],
            [
                'chicken_batch_id' => $batch->id,
                'quantity_purchased' => 20,
                'unit' => 'vials',
                'quantity_used' => 3,
                'remaining_quantity' => 17,
                'expiry_date' => now()->addMonths(5)->toDateString(),
                'cost' => 80000,
                'low_stock_threshold' => 5,
                'notes' => 'Vaccination stock kept ready for the next cycle.',
            ]
        );

        MortalityRecord::query()->where('chicken_batch_id', $batch->id)->whereDate('record_date', now()->subDays(3)->toDateString())->delete();
        MortalityRecord::query()->create([
            'chicken_batch_id' => $batch->id,
            'record_date' => now()->subDays(3)->toDateString(),
            'number_dead' => 3,
            'cause_of_death' => 'Heat stress',
            'number_consumed_home' => 2,
            'notes' => 'Losses recorded during a hot afternoon.',
        ]);

        Sale::query()->where('chicken_batch_id', $batch->id)->where('sale_type', 'chicken')->whereDate('sale_date', now()->subDays(2)->toDateString())->delete();
        Sale::query()->create([
            'chicken_batch_id' => $batch->id,
            'sale_type' => 'chicken',
            'sale_date' => now()->subDays(2)->toDateString(),
            'number_sold' => 18,
            'weight_sold_kg' => 34.2,
            'price_per_unit' => 14500,
            'total_revenue' => 261000,
            'buyer_name' => 'Local market trader',
            'buyer_contact' => 'N/A',
            'notes' => 'Demo chicken sale.',
        ]);

        Sale::query()->where('sale_type', 'egg')->whereDate('sale_date', now()->toDateString())->delete();
        Sale::query()->create([
            'chicken_batch_id' => $batch->id,
            'sale_type' => 'egg',
            'sale_date' => now()->toDateString(),
            'number_sold' => 24,
            'weight_sold_kg' => null,
            'price_per_unit' => 500,
            'total_revenue' => 12000,
            'buyer_name' => 'Household buyer',
            'buyer_contact' => 'N/A',
            'notes' => 'Demo egg sale.',
        ]);

        Expense::query()->updateOrCreate(
            ['category' => 'feed', 'expense_date' => now()->subDays(1)->toDateString()],
            [
                'chicken_batch_id' => $batch->id,
                'amount' => 320000,
                'notes' => 'Starter mash purchase.',
            ]
        );

        Expense::query()->updateOrCreate(
            ['category' => 'medicine', 'expense_date' => now()->subDays(1)->toDateString()],
            [
                'chicken_batch_id' => $batch->id,
                'amount' => 80000,
                'notes' => 'Vaccination supplies.',
            ]
        );
    }
}
