@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ __('messages.growth_tracking_title') }}</h1>
                <p class="mt-2 text-slate-300">{{ __('messages.growth_tracking_subtitle') }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-700 hover:bg-slate-600 px-4 py-2 text-white transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                {{ __('messages.back') }}
            </a>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.add_growth_record') }}</h2>
                <form method="POST" action="{{ route('growth.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.select_batch') }}</label>
                        <select name="chicken_batch_id" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                            <option value="">{{ __('messages.choose_batch') }}</option>
                            @foreach($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->batch_id }} - {{ $batch->breed_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.week_number') }}</label>
                        <input type="number" name="week_number" required min="1" class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.average_weight_kg') }}</label>
                        <input type="number" step="0.01" name="average_weight_kg" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.feed_consumed_kg') }}</label>
                        <input type="number" step="0.01" name="feed_consumed_kg" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.health_status') }}</label>
                        <select name="health_status" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                            <option value="">Select status...</option>
                            <option value="Good">Good</option>
                            <option value="Fair">Fair</option>
                            <option value="Poor">Poor</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.mortality_recorded') }}</label>
                        <input type="number" name="mortality_recorded" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.notes') }}</label>
                        <textarea name="notes" rows="3" class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300"></textarea>
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-blue-400 px-4 py-2 font-semibold text-slate-950 hover:bg-blue-300 transition">{{ __('messages.record_growth') }}</button>
                </form>
            </div>

            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.recent_growth_records') }}</h2>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($growthRecords as $record)
                    <div onclick="showGrowthChartFromRecord(this)" class="rounded-lg border border-blue-500/20 bg-blue-500/5 p-3 cursor-pointer transition hover:bg-blue-500/10 hover:border-blue-500/40" data-batch-id="{{ $record->chicken_batch_id }}" data-batch-name="{{ $record->batch->batch_id }}" data-week="{{ $record->week_number }}" data-weight="{{ $record->average_weight_kg }}" data-feed="{{ $record->feed_consumed_kg }}" data-health="{{ $record->health_status }}" data-mortality="{{ $record->mortality_recorded }}">
                        <p class="font-semibold text-blue-300">Week {{ $record->week_number }} - {{ $record->batch->batch_id }}</p>
                        <p class="text-sm text-slate-300">Weight: {{ $record->average_weight_kg }}kg | Feed: {{ $record->feed_consumed_kg }}kg</p>
                        <p class="text-xs text-slate-400">Health: {{ $record->health_status }}</p>
                    </div>
                    @empty
                    <p class="text-slate-400">{{ __('messages.no_growth_records') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Growth Chart Modal -->
    <div id="growthModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur z-50 flex items-center justify-center p-4">
        <div class="bg-slate-900 rounded-lg border border-white/10 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 flex items-center justify-between bg-slate-950 border-b border-white/10 px-6 py-4">
                <div>
                    <h3 class="text-xl font-semibold text-white">Growth Analysis - <span id="batchTitle" class="text-blue-300">BATCH</span></h3>
                    <p class="text-sm text-slate-400 mt-1">Visual trend analysis of weight and feed consumption</p>
                </div>
                <button onclick="closeGrowthModal()" class="text-slate-400 hover:text-white transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <!-- Weight Chart -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Average Weight Progression (kg)</h4>
                    <div class="bg-slate-800/50 rounded-lg p-4">
                        <canvas id="weightChart" height="80"></canvas>
                    </div>
                </div>

                <!-- Feed Chart -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Feed Consumed Progression (kg)</h4>
                    <div class="bg-slate-800/50 rounded-lg p-4">
                        <canvas id="feedChart" height="80"></canvas>
                    </div>
                </div>

                <!-- Stats Table -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Detailed Records</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="text-left px-3 py-2 text-slate-300">Week</th>
                                    <th class="text-left px-3 py-2 text-slate-300">Weight (kg)</th>
                                    <th class="text-left px-3 py-2 text-slate-300">Feed (kg)</th>
                                    <th class="text-left px-3 py-2 text-slate-300">Health</th>
                                    <th class="text-left px-3 py-2 text-slate-300">Mortality</th>
                                </tr>
                            </thead>
                            <tbody id="statsTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    // Store all batch records for this page
    const pageGrowthRecords = {!! json_encode($growthRecords->groupBy('chicken_batch_id')->map(function($group) {
        return $group->map(function($r) {
            return [
                'id' => $r->id,
                'batch_id' => $r->chicken_batch_id,
                'batch_name' => $r->batch->batch_id,
                'week' => $r->week_number,
                'weight' => (float)$r->average_weight_kg,
                'feed' => (float)$r->feed_consumed_kg,
                'health' => $r->health_status,
                'mortality' => (int)$r->mortality_recorded,
            ];
        })->toArray();
    })->toArray()) !!};

    function showGrowthChartFromRecord(element) {
        const batchId = element.dataset.batchId;
        const batchName = element.dataset.batchName;
        
        // Get all records for this batch from pageGrowthRecords
        const records = [];
        for (const batch in pageGrowthRecords) {
            pageGrowthRecords[batch].forEach(r => {
                if (r.batch_id == batchId) {
                    records.push(r);
                }
            });
        }
        
        if (records.length === 0) return;
        
        // Sort by week
        records.sort((a, b) => a.week - b.week);
        
        // Show modal and render charts
        document.getElementById('batchTitle').textContent = batchName;
        document.getElementById('growthModal').classList.remove('hidden');
        
        const weeks = records.map(r => 'Week ' + r.week);
        const weights = records.map(r => r.weight);
        const feeds = records.map(r => r.feed);

        // Weight Chart
        const weightCtx = document.getElementById('weightChart');
        if (weightCtx) {
            const ctx = weightCtx.getContext('2d');
            if (window.currentChart) window.currentChart.destroy();
            window.currentChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: weeks,
                    datasets: [{
                        label: 'Average Weight (kg)',
                        data: weights,
                        borderColor: '#60a5fa',
                        backgroundColor: 'rgba(96, 165, 250, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#60a5fa',
                        pointHoverRadius: 7,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { labels: { color: '#cbd5e1' } } },
                    scales: {
                        y: { ticks: { color: '#cbd5e1' }, grid: { color: 'rgba(148, 163, 184, 0.1)' } },
                        x: { ticks: { color: '#cbd5e1' }, grid: { color: 'rgba(148, 163, 184, 0.1)' } }
                    }
                }
            });
        }

        // Feed Chart
        const feedCtx = document.getElementById('feedChart');
        if (feedCtx) {
            const ctx = feedCtx.getContext('2d');
            if (window.currentChart2) window.currentChart2.destroy();
            window.currentChart2 = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: weeks,
                    datasets: [{
                        label: 'Feed Consumed (kg)',
                        data: feeds,
                        backgroundColor: '#34d399',
                        borderColor: '#10b981',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { labels: { color: '#cbd5e1' } } },
                    scales: {
                        y: { ticks: { color: '#cbd5e1' }, grid: { color: 'rgba(148, 163, 184, 0.1)' } },
                        x: { ticks: { color: '#cbd5e1' }, grid: { color: 'rgba(148, 163, 184, 0.1)' } }
                    }
                }
            });
        }

        // Populate stats table
        const tbody = document.getElementById('statsTableBody');
        if (tbody) {
            tbody.innerHTML = records.map(r => `
                <tr class="border-b border-white/10">
                    <td class="px-3 py-2 text-slate-300">Week ${r.week}</td>
                    <td class="px-3 py-2 text-green-400">${r.weight.toFixed(2)}</td>
                    <td class="px-3 py-2 text-blue-400">${r.feed.toFixed(2)}</td>
                    <td class="px-3 py-2 text-amber-400">${r.health}</td>
                    <td class="px-3 py-2 text-red-400">${r.mortality}</td>
                </tr>
            `).join('');
        }
    }
</script>
