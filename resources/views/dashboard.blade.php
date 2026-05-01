@php
    $metricCards = [
        ['label' => __('messages.available_chicks'), 'value' => $metrics['availableChicks']],
        ['label' => __('messages.dead_chicks'), 'value' => $metrics['deadChicks']],
        ['label' => __('messages.sold_chickens'), 'value' => $metrics['soldChickens']],
        ['label' => __('messages.consumed_chicks'), 'value' => $metrics['consumedChicks']],
        ['label' => __('messages.egg_collected_today'), 'value' => $metrics['todayEggs']],
        ['label' => __('messages.egg_sold'), 'value' => $metrics['eggSales']],
        ['label' => __('messages.feed_stock'), 'value' => $metrics['feedStock']],
        ['label' => __('messages.medicine_stock'), 'value' => $metrics['medicineStock']],
        ['label' => __('messages.revenue'), 'value' => $metrics['revenue']],
        ['label' => __('messages.expenses'), 'value' => $metrics['expenses']],
        ['label' => __('messages.net_profit'), 'value' => $metrics['profit']],
    ];
@endphp

@extends('layouts.main')

@section('content')
    <section class="space-y-8">
        <div class="rounded-4xl border border-white/10 bg-white/5 p-6 shadow-2xl shadow-black/20 backdrop-blur">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-300/90">{{ __('messages.farm_overview') }}</p>
                    <h2 class="mt-3 text-3xl font-semibold text-white">{{ __('messages.monitor_cycle') }}</h2>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-300">Record batch entry, weekly growth, egg production, mortality, stock usage, sales, and expenses while the dashboard keeps totals, alerts, and profit summaries updated.</p>
                </div>
                <div class="grid grid-cols-2 gap-3 text-sm sm:grid-cols-4">
                    <div class="rounded-2xl border border-white/10 bg-slate-900/70 px-4 py-3">
                        <p class="text-slate-400">{{ __('messages.batches') }}</p>
                        <p class="mt-1 text-xl font-semibold text-white">{{ $batches->count() }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-slate-900/70 px-4 py-3">
                        <p class="text-slate-400">{{ __('messages.active') }}</p>
                        <p class="mt-1 text-xl font-semibold text-white">{{ $activeBatches->count() }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-slate-900/70 px-4 py-3">
                        <p class="text-slate-400">{{ __('messages.growth_rows') }}</p>
                        <p class="mt-1 text-xl font-semibold text-white">{{ $growthRecords->count() }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-slate-900/70 px-4 py-3">
                        <p class="text-slate-400">{{ __('messages.sales_rows') }}</p>
                        <p class="mt-1 text-xl font-semibold text-white">{{ $sales->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
            @foreach($metricCards as $card)
                <div class="rounded-3xl border border-white/10 bg-slate-900/70 p-5 shadow-xl shadow-black/20">
                    <p class="text-sm text-slate-400">{{ $card['label'] }}</p>
                    <p class="mt-3 text-3xl font-semibold text-white">{{ number_format($card['value']) }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
            <div class="rounded-4xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-black/20" id="notifications">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-300/90">{{ __('messages.notifications') }}</p>
                        <h3 class="mt-2 text-2xl font-semibold text-white">{{ __('messages.alerts_reminders') }}</h3>
                    </div>
                    <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-300">{{ $notifications->count() }} {{ __('messages.active_label') }}</span>
                </div>

                <div class="mt-6 grid gap-3">
                    @forelse($notifications as $notification)
                        <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-200">{{ $notification }}</div>
                    @empty
                        <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-200">{{ __('messages.no_alerts') }}</div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-4xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-black/20">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-emerald-300/90">{{ __('messages.weekly_growth_progress') }}</p>
                <h3 class="mt-2 text-2xl font-semibold text-white">{{ __('messages.recent_growth') }}</h3>
                <div class="mt-5 space-y-4">
                    @forelse($weeklyGrowth as $record)
                        @php($width = min(100, max(10, (float) $record->average_weight_kg * 180)))
                        <div>
                            <div class="flex items-center justify-between text-sm text-slate-300">
                                <span>Week {{ $record->week_number }} - {{ $record->batch?->batch_id }}</span>
                                <span>{{ number_format($record->average_weight_kg, 2) }} kg</span>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-white/10">
                                <div class="h-2 rounded-full bg-linear-to-r from-amber-400 to-emerald-400" style="width: {{ $width }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-300">{{ __('messages.no_growth_records_short') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <section class="space-y-6" id="batches">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-300/90">{{ __('messages.batch_management_short') }}</p>
                <h3 class="mt-2 text-2xl font-semibold text-white">{{ __('messages.register_new_chick_batch') }}</h3>
            </div>

            <div class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
                <form method="POST" action="{{ route('batches.store') }}" class="rounded-4xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-black/20">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">{{ __('messages.date_of_arrival') }}</label>
                            <input type="date" name="date_of_arrival" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">{{ __('messages.chicken_type') }}</label>
                            <input name="chicken_type" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" placeholder="Broiler, Layer, Local" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">{{ __('messages.breed_name') }}</label>
                            <input name="breed_name" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">{{ __('messages.number_entered') }}</label>
                            <input type="number" min="1" name="number_entered" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">{{ __('messages.initial_average_weight_kg') }}</label>
                            <input type="number" step="0.01" min="0" name="initial_average_weight_kg" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">{{ __('messages.supplier_source') }}</label>
                            <input name="supplier_source" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">{{ __('messages.purchase_cost') }}</label>
                            <input type="number" step="0.01" min="0" name="purchase_cost" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">{{ __('messages.expected_purpose') }}</label>
                            <input name="expected_purpose" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" placeholder="Meat or Egg production" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">{{ __('messages.status') }}</label>
                            <select name="status" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                                <option value="active">Active</option>
                                <option value="sold">Sold</option>
                                <option value="finished">Finished</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">{{ __('messages.next_vaccination_date') }}</label>
                            <input type="date" name="next_vaccination_at" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.notes') }}</label>
                        <textarea name="notes" rows="4" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white"></textarea>
                    </div>
                    <button class="mt-5 rounded-2xl bg-amber-400 px-5 py-3 font-semibold text-slate-950 transition hover:bg-amber-300">{{ __('messages.save_batch') }}</button>
                </form>

                <div class="rounded-4xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-black/20">
                    <div class="overflow-hidden rounded-3xl border border-white/10">
                        <table class="min-w-full divide-y divide-white/10 text-sm">
                            <thead class="bg-white/5 text-left text-slate-300">
                                <tr>
                                    <th class="px-4 py-3">{{ __('messages.batch_id') }}</th>
                                    <th class="px-4 py-3">{{ __('messages.type') }}</th>
                                    <th class="px-4 py-3">{{ __('messages.entered') }}</th>
                                    <th class="px-4 py-3">{{ __('messages.status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($batches as $batch)
                                    <tr class="text-slate-200">
                                        <td class="px-4 py-3 font-medium text-white">{{ $batch->batch_id }}</td>
                                        <td class="px-4 py-3">{{ $batch->chicken_type }} / {{ $batch->breed_name }}</td>
                                        <td class="px-4 py-3">{{ number_format($batch->number_entered) }}</td>
                                        <td class="px-4 py-3">{{ ucfirst($batch->status) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-2" id="growth">
            <form method="POST" action="{{ route('growth.store') }}" class="rounded-4xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-black/20">
                @csrf
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-emerald-300/90">{{ __('messages.growth_tracking_short') }}</p>
                <h3 class="mt-2 text-2xl font-semibold text-white">{{ __('messages.record_weekly_growth') }}</h3>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.batch_id') }}</label>
                        <select name="chicken_batch_id" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                            @foreach($activeBatches as $batch)
                                <option value="{{ $batch->id }}">{{ $batch->batch_id }} - {{ $batch->breed_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.week_number') }}</label>
                        <input type="number" min="1" name="week_number" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.average_weight_kg') }}</label>
                        <input type="number" step="0.01" min="0" name="average_weight_kg" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.feed_consumed_kg') }}</label>
                        <input type="number" step="0.01" min="0" name="feed_consumed_kg" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.health_status') }}</label>
                        <input name="health_status" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.mortality_recorded') }}</label>
                        <input type="number" min="0" name="mortality_recorded" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="mb-2 block text-sm text-slate-300">{{ __('messages.notes') }}</label>
                    <textarea name="notes" rows="4" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white"></textarea>
                </div>
                <button class="mt-5 rounded-2xl bg-emerald-400 px-5 py-3 font-semibold text-slate-950 transition hover:bg-emerald-300">{{ __('messages.save_growth_record') }}</button>
            </form>

            <div class="rounded-4xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-black/20">
                <div class="overflow-hidden rounded-3xl border border-white/10">
                    <table class="min-w-full divide-y divide-white/10 text-sm">
                        <thead class="bg-white/5 text-left text-slate-300">
                            <tr>
                                <th class="px-4 py-3">{{ __('messages.batch_id') }}</th>
                                <th class="px-4 py-3">{{ __('messages.week_number') }}</th>
                                <th class="px-4 py-3">{{ __('messages.average_weight_kg') }}</th>
                                <th class="px-4 py-3">{{ __('messages.health_status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($growthRecords as $record)
                                <tr class="text-slate-200">
                                    <td class="px-4 py-3">{{ $record->batch?->batch_id }}</td>
                                    <td class="px-4 py-3">Week {{ $record->week_number }}</td>
                                    <td class="px-4 py-3">{{ number_format($record->average_weight_kg, 2) }} kg</td>
                                    <td class="px-4 py-3">{{ $record->health_status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-2" id="eggs">
            <form method="POST" action="{{ route('eggs.store') }}" class="rounded-4xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-black/20">
                @csrf
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-300/90">{{ __('messages.egg_production_short') }}</p>
                <h3 class="mt-2 text-2xl font-semibold text-white">{{ __('messages.daily_egg_record') }}</h3>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.batch_id') }}</label>
                        <select name="chicken_batch_id" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                            @foreach($activeBatches as $batch)
                                <option value="{{ $batch->id }}">{{ $batch->batch_id }} - {{ $batch->breed_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.date') }}</label>
                        <input type="date" name="record_date" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.egg_collected_today') }}</label>
                        <input type="number" min="0" name="total_eggs_produced" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Broken eggs</label>
                        <input type="number" min="0" name="broken_eggs" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Eggs consumed at home</label>
                        <input type="number" min="0" name="eggs_consumed_home" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.egg_sold') }}</label>
                        <input type="number" min="0" name="eggs_sold" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="mb-2 block text-sm text-slate-300">Notes</label>
                    <textarea name="notes" rows="4" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white"></textarea>
                </div>
                <button class="mt-5 rounded-2xl bg-amber-400 px-5 py-3 font-semibold text-slate-950 transition hover:bg-amber-300">{{ __('messages.save_egg_record') }}</button>
            </form>

            <div class="rounded-4xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-black/20">
                <div class="overflow-hidden rounded-3xl border border-white/10">
                    <table class="min-w-full divide-y divide-white/10 text-sm">
                        <thead class="bg-white/5 text-left text-slate-300">
                            <tr>
                                <th class="px-4 py-3">{{ __('messages.batch_id') }}</th>
                                <th class="px-4 py-3">{{ __('messages.date') }}</th>
                                <th class="px-4 py-3">{{ __('messages.total_eggs_today') }}</th>
                                <th class="px-4 py-3">Remaining</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($eggRecords as $record)
                                <tr class="text-slate-200">
                                    <td class="px-4 py-3">{{ $record->batch?->batch_id }}</td>
                                    <td class="px-4 py-3">{{ $record->record_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3">{{ number_format($record->total_eggs_produced) }}</td>
                                    <td class="px-4 py-3">{{ number_format($record->remaining_eggs) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-2" id="stock">
            <form method="POST" action="{{ route('stocks.store') }}" class="rounded-4xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-black/20">
                @csrf
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-cyan-300/90">{{ __('messages.feed_medicine_store') }}</p>
                <h3 class="mt-2 text-2xl font-semibold text-white">{{ __('messages.record_supply_stock') }}</h3>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.type') }}</label>
                        <select name="category" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                            <option value="feed">Feed</option>
                            <option value="medicine">Medicine</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Item name</label>
                        <input name="item_name" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Quantity purchased</label>
                        <input type="number" step="0.01" min="0" name="quantity_purchased" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Unit</label>
                        <input name="unit" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" placeholder="bags, kg, vials" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Quantity used</label>
                        <input type="number" step="0.01" min="0" name="quantity_used" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Remaining stock</label>
                        <input type="number" step="0.01" min="0" name="remaining_quantity" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Purchase date</label>
                        <input type="date" name="purchase_date" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Expiry date</label>
                        <input type="date" name="expiry_date" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Cost</label>
                        <input type="number" step="0.01" min="0" name="cost" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Low stock threshold</label>
                        <input type="number" step="0.01" min="0" name="low_stock_threshold" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="mb-2 block text-sm text-slate-300">{{ __('messages.notes') }}</label>
                    <textarea name="notes" rows="4" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white"></textarea>
                </div>
                <button class="mt-5 rounded-2xl bg-cyan-400 px-5 py-3 font-semibold text-slate-950 transition hover:bg-cyan-300">{{ __('messages.save_stock_record') }}</button>
            </form>

            <div class="rounded-4xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-black/20">
                <div class="overflow-hidden rounded-3xl border border-white/10">
                    <table class="min-w-full divide-y divide-white/10 text-sm">
                        <thead class="bg-white/5 text-left text-slate-300">
                            <tr>
                                <th class="px-4 py-3">Item</th>
                                <th class="px-4 py-3">Category</th>
                                <th class="px-4 py-3">Remaining</th>
                                <th class="px-4 py-3">Expiry</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($stocks as $stock)
                                <tr class="text-slate-200">
                                    <td class="px-4 py-3">{{ $stock->item_name }}</td>
                                    <td class="px-4 py-3">{{ ucfirst($stock->category) }}</td>
                                    <td class="px-4 py-3">{{ number_format($stock->remaining_quantity, 2) }} {{ $stock->unit }}</td>
                                    <td class="px-4 py-3">{{ $stock->expiry_date?->format('M d, Y') ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-2" id="mortality">
            <form method="POST" action="{{ route('mortality.store') }}" class="rounded-4xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-black/20">
                @csrf
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-rose-300/90">{{ __('messages.mortality_consumption_short') }}</p>
                <h3 class="mt-2 text-2xl font-semibold text-white">{{ __('messages.record_mortality') }}</h3>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.batch_id') }}</label>
                        <select name="chicken_batch_id" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                            @foreach($activeBatches as $batch)
                                <option value="{{ $batch->id }}">{{ $batch->batch_id }} - {{ $batch->breed_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.date') }}</label>
                        <input type="date" name="record_date" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.dead_chicks') }}</label>
                        <input type="number" min="0" name="number_dead" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Cause of death</label>
                        <input name="cause_of_death" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.consumed_chicks') }}</label>
                        <input type="number" min="0" name="number_consumed_home" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="mb-2 block text-sm text-slate-300">{{ __('messages.notes') }}</label>
                    <textarea name="notes" rows="4" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white"></textarea>
                </div>
                <button class="mt-5 rounded-2xl bg-rose-400 px-5 py-3 font-semibold text-slate-950 transition hover:bg-rose-300">{{ __('messages.save_mortality_record') }}</button>
            </form>

            <div class="rounded-4xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-black/20">
                <div class="overflow-hidden rounded-3xl border border-white/10">
                    <table class="min-w-full divide-y divide-white/10 text-sm">
                        <thead class="bg-white/5 text-left text-slate-300">
                            <tr>
                                <th class="px-4 py-3">{{ __('messages.batch_id') }}</th>
                                <th class="px-4 py-3">{{ __('messages.dead_chicks') }}</th>
                                <th class="px-4 py-3">{{ __('messages.consumed_chicks') }}</th>
                                <th class="px-4 py-3">Cause</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($mortalityRecords as $record)
                                <tr class="text-slate-200">
                                    <td class="px-4 py-3">{{ $record->batch?->batch_id }}</td>
                                    <td class="px-4 py-3">{{ number_format($record->number_dead) }}</td>
                                    <td class="px-4 py-3">{{ number_format($record->number_consumed_home) }}</td>
                                    <td class="px-4 py-3">{{ $record->cause_of_death ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-2" id="sales">
            <form method="POST" action="{{ route('sales.store') }}" class="rounded-4xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-black/20">
                @csrf
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-emerald-300/90">{{ __('messages.sales_short') }}</p>
                <h3 class="mt-2 text-2xl font-semibold text-white">{{ __('messages.record_sale') }}</h3>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Sale type</label>
                        <select name="sale_type" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                            <option value="chicken">Chicken</option>
                            <option value="egg">Egg</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.batch_id') }}</label>
                        <select name="chicken_batch_id" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white">
                            <option value="">Optional</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}">{{ $batch->batch_id }} - {{ $batch->breed_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Number sold</label>
                        <input type="number" min="0" name="number_sold" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Weight sold (kg)</label>
                        <input type="number" step="0.01" min="0" name="weight_sold_kg" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Price per unit</label>
                        <input type="number" step="0.01" min="0" name="price_per_unit" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.date') }}</label>
                        <input type="date" name="sale_date" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Buyer name</label>
                        <input name="buyer_name" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Buyer contact</label>
                        <input name="buyer_contact" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="mb-2 block text-sm text-slate-300">{{ __('messages.notes') }}</label>
                    <textarea name="notes" rows="4" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white"></textarea>
                </div>
                <button class="mt-5 rounded-2xl bg-emerald-400 px-5 py-3 font-semibold text-slate-950 transition hover:bg-emerald-300">{{ __('messages.save_sale_record') }}</button>
            </form>

            <div class="rounded-4xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-black/20">
                <div class="overflow-hidden rounded-3xl border border-white/10">
                    <table class="min-w-full divide-y divide-white/10 text-sm">
                        <thead class="bg-white/5 text-left text-slate-300">
                            <tr>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Number</th>
                                <th class="px-4 py-3">Revenue</th>
                                <th class="px-4 py-3">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($sales as $sale)
                                <tr class="text-slate-200">
                                    <td class="px-4 py-3">{{ ucfirst($sale->sale_type) }}</td>
                                    <td class="px-4 py-3">{{ number_format($sale->number_sold) }}</td>
                                    <td class="px-4 py-3">{{ number_format($sale->total_revenue) }}</td>
                                    <td class="px-4 py-3">{{ $sale->sale_date->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-2" id="expenses">
            <form method="POST" action="{{ route('expenses.store') }}" class="rounded-4xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-black/20">
                @csrf
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-violet-300/90">{{ __('messages.financial_records_short') }}</p>
                <h3 class="mt-2 text-2xl font-semibold text-white">{{ __('messages.record_expense') }}</h3>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.date') }}</label>
                        <input type="date" name="expense_date" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.type') }}</label>
                        <input name="category" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" placeholder="Feed, medicine, labor, transport" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Amount</label>
                        <input type="number" step="0.01" min="0" name="amount" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white" required>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="mb-2 block text-sm text-slate-300">{{ __('messages.notes') }}</label>
                    <textarea name="notes" rows="4" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-white"></textarea>
                </div>
                <button class="mt-5 rounded-2xl bg-violet-400 px-5 py-3 font-semibold text-slate-950 transition hover:bg-violet-300">{{ __('messages.save_expense') }}</button>
            </form>

            <div class="rounded-4xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-black/20">
                <div class="overflow-hidden rounded-3xl border border-white/10">
                    <table class="min-w-full divide-y divide-white/10 text-sm">
                        <thead class="bg-white/5 text-left text-slate-300">
                            <tr>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Category</th>
                                <th class="px-4 py-3">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($expenses as $expense)
                                <tr class="text-slate-200">
                                    <td class="px-4 py-3">{{ $expense->expense_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3">{{ $expense->category }}</td>
                                    <td class="px-4 py-3">{{ number_format($expense->amount) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        @if(auth()->user()?->role === 'admin')
            <section class="space-y-6" id="users">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-300/90">User management</p>
                    <h3 class="mt-2 text-2xl font-semibold text-white">Admin-only role control</h3>
                </div>

                <div class="rounded-4xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-black/20">
                    <div class="overflow-hidden rounded-3xl border border-white/10">
                        <table class="min-w-full divide-y divide-white/10 text-sm">
                            <thead class="bg-white/5 text-left text-slate-300">
                                <tr>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Email</th>
                                    <th class="px-4 py-3">Role</th>
                                    <th class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($users as $user)
                                    <tr class="text-slate-200">
                                        <td class="px-4 py-3">{{ $user->name }}</td>
                                        <td class="px-4 py-3">{{ $user->email }}</td>
                                        <td class="px-4 py-3">{{ ucfirst($user->role) }}</td>
                                        <td class="px-4 py-3">
                                            <form method="POST" action="{{ route('users.role', $user) }}" class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="role" class="rounded-2xl border border-white/10 bg-slate-950/70 px-3 py-2 text-white">
                                                    <option value="worker" @selected($user->role === 'worker')>Worker</option>
                                                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                                </select>
                                                <button class="rounded-2xl bg-white/10 px-3 py-2 text-sm font-medium text-white">Update</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endif
    </section>
@endsection