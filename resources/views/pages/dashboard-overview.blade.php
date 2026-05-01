@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Header with Back Button -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ __('messages.dashboard_overview') }}</h1>
                <p class="mt-2 text-slate-300">{{ __('messages.key_metrics_alerts') }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-700 hover:bg-slate-600 px-4 py-2 text-white transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                {{ __('messages.back') }}
            </a>
        </div>

        <!-- Metrics Grid -->
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
                <p class="text-sm text-slate-400">{{ __('messages.feed_stock_bags') }}</p>
                <p class="mt-2 text-3xl font-bold text-blue-400">{{ $metrics['feedStock'] }}</p>
            </div>
            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <p class="text-sm text-slate-400">{{ __('messages.net_profit') }}</p>
                <p class="mt-2 text-3xl font-bold text-emerald-400">{{ number_format($metrics['profit'], 0) }}</p>
            </div>
        </div>

        <!-- Notifications & Alerts -->
        @if($notifications->count())
        <div class="mb-8">
            <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.alerts_notifications') }}</h2>
            <div class="space-y-2">
                @foreach($notifications as $notification)
                <div class="flex items-start gap-3 rounded-lg bg-amber-500/10 border border-amber-500/20 p-4 text-amber-100">
                    <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <p class="text-sm">{{ $notification }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <p class="text-sm text-slate-400">{{ __('messages.total_revenue') }}</p>
                <p class="mt-2 text-2xl font-bold text-green-400">{{ number_format($metrics['revenue'], 0) }}</p>
            </div>
            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <p class="text-sm text-slate-400">{{ __('messages.total_expenses') }}</p>
                <p class="mt-2 text-2xl font-bold text-red-400">{{ number_format($metrics['expenses'], 0) }}</p>
            </div>
            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <p class="text-sm text-slate-400">{{ __('messages.medicine_stock') }}</p>
                <p class="mt-2 text-2xl font-bold text-purple-400">{{ $metrics['medicineStock'] }} vials</p>
            </div>
        </div>
    </div>
</div>
@endsection
