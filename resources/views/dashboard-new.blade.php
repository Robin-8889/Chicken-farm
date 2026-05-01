@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white">{{ __('messages.welcome_user', ['name' => auth()->user()->name]) }}</h1>
            <p class="mt-2 text-slate-300">{{ __('messages.manage_farm_precision') }}</p>
        </div>

        <!-- Key Metrics -->
        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <p class="text-sm text-slate-400">{{ __('messages.available_chicks') }}</p>
                <p class="mt-2 text-3xl font-bold text-amber-300">{{ $metrics['availableChicks'] }}</p>
            </div>
            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <p class="text-sm text-slate-400">{{ __('messages.total_eggs_today') }}</p>
                <p class="mt-2 text-3xl font-bold text-green-400">{{ $metrics['todayEggs'] }}</p>
            </div>
            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <p class="text-sm text-slate-400">{{ __('messages.revenue') }}</p>
                <p class="mt-2 text-3xl font-bold text-emerald-400">{{ number_format($metrics['revenue'], 0) }}</p>
            </div>
            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <p class="text-sm text-slate-400">{{ __('messages.net_profit') }}</p>
                <p class="mt-2 text-3xl font-bold {{ $metrics['profit'] >= 0 ? 'text-blue-400' : 'text-red-400' }}">{{ number_format($metrics['profit'], 0) }}</p>
            </div>
        </div>

        <!-- Alerts -->
        @if($notifications->count())
        <div class="mb-8">
            <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.alerts_notifications') }}</h2>
            <div class="grid gap-3 max-h-32 overflow-y-auto">
                @foreach($notifications->take(5) as $notification)
                <div class="flex items-start gap-3 rounded-lg bg-amber-500/10 border border-amber-500/20 p-3 text-amber-100 text-sm">
                    <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <p>{{ $notification }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Module Navigation -->
        <div class="space-y-4">
            <h2 class="text-2xl font-bold text-white">{{ __('messages.quick_access') }}</h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                <a href="{{ route('dashboard.overview') }}" class="group relative overflow-hidden rounded-lg border border-white/10 bg-white/5 p-6 transition hover:bg-white/10 hover:border-blue-400/50">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-blue-500/20 p-3">
                            <svg class="h-6 w-6 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h12a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6z"></path></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-white">{{ __('messages.dashboard_short') }}</p>
                            <p class="text-sm text-slate-400">{{ __('messages.overview_metrics_short') }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('batch-management') }}" class="group relative overflow-hidden rounded-lg border border-white/10 bg-white/5 p-6 transition hover:bg-white/10 hover:border-amber-400/50">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-amber-500/20 p-3">
                            <svg class="h-6 w-6 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M5 11a1 1 0 011-1h2a1 1 0 011 1v3a1 1 0 01-1 1H6a1 1 0 01-1-1v-3zM14 11a1 1 0 011-1h2a1 1 0 011 1v3a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3z"></path></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-white">{{ __('messages.batch_management_short_label') }}</p>
                            <p class="text-sm text-slate-400">{{ __('messages.register_track_batches_short') }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('growth-tracking') }}" class="group relative overflow-hidden rounded-lg border border-white/10 bg-white/5 p-6 transition hover:bg-white/10 hover:border-green-400/50">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-green-500/20 p-3">
                            <svg class="h-6 w-6 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-white">{{ __('messages.growth_tracking_short_label') }}</p>
                            <p class="text-sm text-slate-400">{{ __('messages.record_weekly_progress_short') }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('egg-production') }}" class="group relative overflow-hidden rounded-lg border border-white/10 bg-white/5 p-6 transition hover:bg-white/10 hover:border-purple-400/50">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-purple-500/20 p-3">
                            <svg class="h-6 w-6 text-purple-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-white">{{ __('messages.egg_production_short_label') }}</p>
                            <p class="text-sm text-slate-400">{{ __('messages.daily_egg_records_short') }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('feed-medicine') }}" class="group relative overflow-hidden rounded-lg border border-white/10 bg-white/5 p-6 transition hover:bg-white/10 hover:border-red-400/50">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-red-500/20 p-3">
                            <svg class="h-6 w-6 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-white">{{ __('messages.feed_medicine_short_label') }}</p>
                            <p class="text-sm text-slate-400">{{ __('messages.inventory_management_short') }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('mortality-consumption') }}" class="group relative overflow-hidden rounded-lg border border-white/10 bg-white/5 p-6 transition hover:bg-white/10 hover:border-orange-400/50">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-orange-500/20 p-3">
                            <svg class="h-6 w-6 text-orange-400" fill="currentColor" viewBox="0 0 20 20"><path d="M13 7H7v6h6V7z"></path></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-white">{{ __('messages.mortality_consumption_short_label') }}</p>
                            <p class="text-sm text-slate-400">{{ __('messages.record_losses_short') }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('sales-page') }}" class="group relative overflow-hidden rounded-lg border border-white/10 bg-white/5 p-6 transition hover:bg-white/10 hover:border-emerald-400/50">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-emerald-500/20 p-3">
                            <svg class="h-6 w-6 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-white">{{ __('messages.sales_short_label') }}</p>
                            <p class="text-sm text-slate-400">{{ __('messages.record_transactions_short') }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('financial-records') }}" class="group relative overflow-hidden rounded-lg border border-white/10 bg-white/5 p-6 transition hover:bg-white/10 hover:border-cyan-400/50">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-cyan-500/20 p-3">
                            <svg class="h-6 w-6 text-cyan-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-white">{{ __('messages.financial_records_short_label') }}</p>
                            <p class="text-sm text-slate-400">{{ __('messages.revenue_expenses_short') }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
