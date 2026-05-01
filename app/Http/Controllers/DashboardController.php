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
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalChicksEntered = ChickenBatch::sum('number_entered');
        $totalDeadChicks = MortalityRecord::sum('number_dead');
        $totalConsumedChicks = MortalityRecord::sum('number_consumed_home');
        $totalSoldChickens = Sale::where('sale_type', 'chicken')->sum('number_sold');
        $availableChicks = max(0, $totalChicksEntered - $totalDeadChicks - $totalConsumedChicks - $totalSoldChickens);

        $todayEggs = EggRecord::whereDate('record_date', today())->sum('total_eggs_produced');
        $totalEggsSold = Sale::where('sale_type', 'egg')->sum('number_sold');
        $currentFeedStock = FarmStock::where('category', 'feed')->sum('remaining_quantity');
        $currentMedicineStock = FarmStock::where('category', 'medicine')->sum('remaining_quantity');
        $totalRevenue = Sale::sum('total_revenue');
        $totalExpenses = Expense::sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;

        $weeklyGrowth = GrowthRecord::query()
            ->with('batch:id,batch_id,breed_name')
            ->latest()
            ->limit(8)
            ->get();

        $notifications = collect();

        foreach (FarmStock::query()->where('remaining_quantity', '<=', 10)->get() as $stock) {
            $notifications->push("Low stock alert: {$stock->item_name} has {$stock->remaining_quantity} {$stock->unit} remaining.");
        }

        foreach (FarmStock::query()->where('category', 'medicine')->whereNotNull('expiry_date')->whereDate('expiry_date', '<=', now()->addDays(30))->get() as $stock) {
            $notifications->push("Expiry warning: {$stock->item_name} expires on {$stock->expiry_date->format('M d, Y')}.");
        }

        foreach (ChickenBatch::query()->whereNotNull('next_vaccination_at')->whereDate('next_vaccination_at', '<=', now()->addDays(7))->get() as $batch) {
            $notifications->push("Vaccination reminder: batch {$batch->batch_id} is due on {$batch->next_vaccination_at->format('M d, Y')}.");
        }

        return view('dashboard-new', [
            'metrics' => [
                'availableChicks' => $availableChicks,
                'deadChicks' => $totalDeadChicks,
                'soldChickens' => $totalSoldChickens,
                'consumedChicks' => $totalConsumedChicks,
                'todayEggs' => $todayEggs,
                'eggSales' => $totalEggsSold,
                'feedStock' => $currentFeedStock,
                'medicineStock' => $currentMedicineStock,
                'revenue' => $totalRevenue,
                'expenses' => $totalExpenses,
                'profit' => $netProfit,
            ],
            'notifications' => $notifications,
            'batches' => ChickenBatch::latest()->get(),
            'growthRecords' => GrowthRecord::with('batch')->latest()->get(),
            'eggRecords' => EggRecord::with('batch')->latest()->get(),
            'stocks' => FarmStock::latest()->get(),
            'mortalityRecords' => MortalityRecord::with('batch')->latest()->get(),
            'sales' => Sale::with('batch')->latest()->get(),
            'expenses' => Expense::latest()->get(),
            'users' => Auth::user()?->role === 'admin' ? User::latest()->get() : collect(),
            'weeklyGrowth' => $weeklyGrowth,
            'activeBatches' => ChickenBatch::where('status', 'active')->latest()->get(),
        ]);
    }

    public function overview(): View
    {
        $totalChicksEntered = ChickenBatch::sum('number_entered');
        $totalDeadChicks = MortalityRecord::sum('number_dead');
        $totalConsumedChicks = MortalityRecord::sum('number_consumed_home');
        $totalSoldChickens = Sale::where('sale_type', 'chicken')->sum('number_sold');
        $availableChicks = max(0, $totalChicksEntered - $totalDeadChicks - $totalConsumedChicks - $totalSoldChickens);

        $todayEggs = EggRecord::whereDate('record_date', today())->sum('total_eggs_produced');
        $totalEggsSold = Sale::where('sale_type', 'egg')->sum('number_sold');
        $currentFeedStock = FarmStock::where('category', 'feed')->sum('remaining_quantity');
        $currentMedicineStock = FarmStock::where('category', 'medicine')->sum('remaining_quantity');
        $totalRevenue = Sale::sum('total_revenue');
        $totalExpenses = Expense::sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;

        $notifications = collect();

        foreach (FarmStock::query()->where('remaining_quantity', '<=', 10)->get() as $stock) {
            $notifications->push("Low stock alert: {$stock->item_name} has {$stock->remaining_quantity} {$stock->unit} remaining.");
        }

        foreach (FarmStock::query()->where('category', 'medicine')->whereNotNull('expiry_date')->whereDate('expiry_date', '<=', now()->addDays(30))->get() as $stock) {
            $notifications->push("Expiry warning: {$stock->item_name} expires on {$stock->expiry_date->format('M d, Y')}.");
        }

        foreach (ChickenBatch::query()->whereNotNull('next_vaccination_at')->whereDate('next_vaccination_at', '<=', now()->addDays(7))->get() as $batch) {
            $notifications->push("Vaccination reminder: batch {$batch->batch_id} is due on {$batch->next_vaccination_at->format('M d, Y')}.");
        }

        return view('pages.dashboard-overview', [
            'metrics' => [
                'availableChicks' => $availableChicks,
                'deadChicks' => $totalDeadChicks,
                'soldChickens' => $totalSoldChickens,
                'consumedChicks' => $totalConsumedChicks,
                'todayEggs' => $todayEggs,
                'eggSales' => $totalEggsSold,
                'feedStock' => $currentFeedStock,
                'medicineStock' => $currentMedicineStock,
                'revenue' => $totalRevenue,
                'expenses' => $totalExpenses,
                'profit' => $netProfit,
            ],
            'notifications' => $notifications,
        ]);
    }

    public function batchManagement(): View
    {
        return view('pages.batch-management', [
            'batches' => ChickenBatch::latest()->get(),
            'activeBatches' => ChickenBatch::where('status', 'active')->latest()->get(),
        ]);
    }

    public function showBatch(ChickenBatch $batch): View
    {
        $batch->load([
            'growthRecords' => fn ($query) => $query->latest(),
            'eggRecords' => fn ($query) => $query->latest(),
            'mortalityRecords' => fn ($query) => $query->latest(),
            'sales' => fn ($query) => $query->latest(),
            'farmStocks' => fn ($query) => $query->latest(),
            'expenses' => fn ($query) => $query->latest(),
        ]);

        $availableChicks = max(
            0,
            (int) $batch->number_entered
            - (int) $batch->mortalityRecords->sum('number_dead')
            - (int) $batch->mortalityRecords->sum('number_consumed_home')
            - (int) $batch->sales->where('sale_type', 'chicken')->sum('number_sold')
        );

        return view('pages.batch-detail', [
            'batch' => $batch,
            'availableChicks' => $availableChicks,
            'totalChickenSales' => $batch->sales->where('sale_type', 'chicken')->sum('total_revenue'),
            'totalEggSales' => $batch->sales->where('sale_type', 'egg')->sum('total_revenue'),
            'feedStocks' => $batch->farmStocks->where('category', 'feed'),
            'medicineStocks' => $batch->farmStocks->where('category', 'medicine'),
            'batchExpenses' => $batch->expenses,
        ]);
    }

    public function growthTracking(): View
    {
        return view('pages.growth-tracking', [
            'batches' => ChickenBatch::latest()->get(),
            'growthRecords' => GrowthRecord::with('batch')->latest()->get(),
        ]);
    }

    public function eggProduction(): View
    {
        return view('pages.egg-production', [
            'batches' => ChickenBatch::latest()->get(),
            'eggRecords' => EggRecord::with('batch')->latest()->get(),
        ]);
    }

    public function feedMedicine(): View
    {
        return view('pages.feed-and-medicine', [
            'batches' => ChickenBatch::latest()->get(),
            'stocks' => FarmStock::latest()->get(),
        ]);
    }

    public function mortalityConsumption(): View
    {
        return view('pages.mortality-and-consumption', [
            'batches' => ChickenBatch::latest()->get(),
            'mortalityRecords' => MortalityRecord::with('batch')->latest()->get(),
        ]);
    }

    public function salesPage(): View
    {
        return view('pages.sales', [
            'batches' => ChickenBatch::latest()->get(),
            'sales' => Sale::with('batch')->latest()->get(),
        ]);
    }

    public function financialRecords(): View
    {
        $totalRevenue = Sale::sum('total_revenue');
        $totalExpenses = Expense::sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;

        return view('pages.financial-records', [
            'batches' => ChickenBatch::latest()->get(),
            'metrics' => [
                'revenue' => $totalRevenue,
                'expenses' => $totalExpenses,
                'profit' => $netProfit,
            ],
            'expenses' => Expense::latest()->get(),
        ]);
    }
}