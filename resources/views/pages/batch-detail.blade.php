@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-amber-300/90">{{ __('messages.batch_details') }}</p>
                <h1 class="text-3xl font-bold text-white">{{ $batch->batch_id }}</h1>
                <p class="mt-2 text-slate-300">{{ $batch->breed_name }} | {{ $batch->chicken_type }} | {{ __('messages.date_of_arrival') }} {{ $batch->date_of_arrival->format('M d, Y') }}</p>
            </div>
            <a href="{{ route('batch-management') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-4 py-2 text-white transition hover:bg-slate-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back to batches
            </a>
        </div>

        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                <p class="text-sm text-slate-400">{{ __('messages.entered') }}</p>
                <p class="mt-2 text-3xl font-bold text-amber-300">{{ $batch->number_entered }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                <p class="text-sm text-slate-400">{{ __('messages.available_now') }}</p>
                <p class="mt-2 text-3xl font-bold text-emerald-300">{{ $availableChicks }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                <p class="text-sm text-slate-400">{{ __('messages.purchase_cost_label') }}</p>
                <p class="mt-2 text-3xl font-bold text-blue-300">{{ number_format($batch->purchase_cost, 0) }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                <p class="text-sm text-slate-400">{{ __('messages.status') }}</p>
                <p class="mt-2 text-3xl font-bold {{ $batch->status === 'active' ? 'text-green-300' : 'text-slate-300' }}">{{ ucfirst($batch->status) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 xl:grid-cols-2">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.sales') }}</h2>
                <div class="space-y-3">
                    <div class="rounded-xl border border-white/10 bg-black/20 p-4">
                        <p class="text-sm text-slate-400">{{ __('messages.chicken_sales_revenue') }}</p>
                        <p class="mt-1 text-2xl font-bold text-emerald-300">{{ number_format($totalChickenSales, 0) }}</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-black/20 p-4">
                        <p class="text-sm text-slate-400">{{ __('messages.egg_sales_revenue') }}</p>
                        <p class="mt-1 text-2xl font-bold text-emerald-300">{{ number_format($totalEggSales, 0) }}</p>
                    </div>
                    <div class="max-h-80 space-y-2 overflow-y-auto">
                        @forelse($batch->sales as $sale)
                        <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/5 p-3">
                            <p class="font-semibold text-emerald-300">{{ ucfirst($sale->sale_type) }} sale</p>
                            <p class="text-sm text-slate-300">{{ $sale->sale_date->format('M d, Y') }} | {{ $sale->number_sold }} units | {{ number_format($sale->total_revenue, 0) }}</p>
                        </div>
                        @empty
                        <p class="text-slate-400">{{ __('messages.no_sales_recorded') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.food_medicine_expenses') }}</h2>
                <div class="space-y-4">
                    <div>
                        <p class="mb-2 text-sm uppercase tracking-[0.2em] text-slate-400">{{ __('messages.food_feed') }}</p>
                        <div class="space-y-2">
                            @forelse($feedStocks as $stock)
                            <div class="rounded-xl border border-white/10 bg-black/20 p-3 text-sm text-slate-300">
                                <p class="font-semibold text-amber-300">{{ $stock->item_name }}</p>
                                <p>Used: {{ $stock->quantity_used }} {{ $stock->unit }} | Remaining: {{ $stock->remaining_quantity }} {{ $stock->unit }}</p>
                                <p>Cost: {{ number_format($stock->cost, 0) }}</p>
                            </div>
                            @empty
                            <p class="text-slate-400">{{ __('messages.no_feed_records') }}</p>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <p class="mb-2 text-sm uppercase tracking-[0.2em] text-slate-400">{{ __('messages.medicine') }}</p>
                        <div class="space-y-2">
                            @forelse($medicineStocks as $stock)
                            <div class="rounded-xl border border-white/10 bg-black/20 p-3 text-sm text-slate-300">
                                <p class="font-semibold text-blue-300">{{ $stock->item_name }}</p>
                                <p>Used: {{ $stock->quantity_used }} {{ $stock->unit }} | Remaining: {{ $stock->remaining_quantity }} {{ $stock->unit }}</p>
                                <p>Expiry: {{ $stock->expiry_date?->format('M d, Y') ?? 'N/A' }} | Cost: {{ number_format($stock->cost, 0) }}</p>
                            </div>
                            @empty
                            <p class="text-slate-400">{{ __('messages.no_medicine_records') }}</p>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <p class="mb-2 text-sm uppercase tracking-[0.2em] text-slate-400">{{ __('messages.expenditures') }}</p>
                        <div class="space-y-2 max-h-56 overflow-y-auto">
                            @forelse($batchExpenses as $expense)
                            <div class="rounded-xl border border-white/10 bg-black/20 p-3 text-sm text-slate-300">
                                <p class="font-semibold text-red-300">{{ ucfirst($expense->category) }}</p>
                                <p>{{ $expense->expense_date->format('M d, Y') }} | {{ number_format($expense->amount, 0) }}</p>
                                <p class="text-xs text-slate-400">{{ $expense->notes }}</p>
                            </div>
                            @empty
                            <p class="text-slate-400">{{ __('messages.no_batch_expenses') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-8 xl:grid-cols-3">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.growth_health') }}</h2>
                <div class="space-y-2 max-h-80 overflow-y-auto">
                    @forelse($batch->growthRecords as $record)
                    <div class="rounded-xl border border-blue-500/20 bg-blue-500/5 p-3 text-sm text-slate-300">
                        <p class="font-semibold text-blue-300">Week {{ $record->week_number }}</p>
                        <p>Weight: {{ $record->average_weight_kg }} kg | Feed: {{ $record->feed_consumed_kg }} kg</p>
                        <p>Health: {{ $record->health_status }} | Mortality: {{ $record->mortality_recorded }}</p>
                    </div>
                    @empty
                    <p class="text-slate-400">{{ __('messages.no_growth_records') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.egg_records') }}</h2>
                <div class="space-y-2 max-h-80 overflow-y-auto">
                    @forelse($batch->eggRecords as $record)
                    <div class="rounded-xl border border-amber-500/20 bg-amber-500/5 p-3 text-sm text-slate-300">
                        <p class="font-semibold text-amber-300">{{ $record->record_date->format('M d, Y') }}</p>
                        <p>Produced: {{ $record->total_eggs_produced }} | Sold: {{ $record->eggs_sold }} | Remaining: {{ $record->remaining_eggs }}</p>
                    </div>
                    @empty
                    <p class="text-slate-400">{{ __('messages.no_egg_records') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.mortality_consumption') }}</h2>
                <div class="space-y-2 max-h-80 overflow-y-auto">
                    @forelse($batch->mortalityRecords as $record)
                    <div class="rounded-xl border border-red-500/20 bg-red-500/5 p-3 text-sm text-slate-300">
                        <p class="font-semibold text-red-300">{{ $record->record_date->format('M d, Y') }}</p>
                        <p>Dead: {{ $record->number_dead }} | Consumed: {{ $record->number_consumed_home }}</p>
                        <p class="text-xs text-slate-400">{{ $record->cause_of_death }}</p>
                    </div>
                    @empty
                    <p class="text-slate-400">{{ __('messages.no_mortality_records') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
