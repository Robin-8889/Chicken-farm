@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ __('messages.sales') }}</h1>
                <p class="mt-2 text-slate-300">{{ __('messages.record_transactions') }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-700 hover:bg-slate-600 px-4 py-2 text-white transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                {{ __('messages.back') }}
            </a>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.record_sale') }}</h2>
                <form method="POST" action="{{ route('sales.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.sale_type') }}</label>
                        <select name="sale_type" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                            <option value="">{{ __('messages.select_type') }}</option>
                            <option value="chicken">{{ __('messages.chicken') }}</option>
                            <option value="egg">{{ __('messages.egg_production_short') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.select_batch_optional') }}</label>
                        <select name="chicken_batch_id" class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                            <option value="">{{ __('messages.choose_batch') }}</option>
                            @foreach($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->batch_id }} - {{ $batch->breed_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.date') }}</label>
                        <input type="date" name="sale_date" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.number_sold') }}</label>
                        <input type="number" name="number_sold" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.weight_sold_kg') }}</label>
                        <input type="number" step="0.01" name="weight_sold_kg" class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.price_per_unit') }}</label>
                        <input type="number" name="price_per_unit" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.total_revenue_label') }}</label>
                        <input type="number" name="total_revenue" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.buyer_name') }}</label>
                        <input type="text" name="buyer_name" class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-emerald-400 px-4 py-2 font-semibold text-slate-950 hover:bg-emerald-300 transition">{{ __('messages.save_sale_record') }}</button>
                </form>
            </div>

            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.recent_sales') }}</h2>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($sales as $sale)
                    <div class="rounded-lg border border-emerald-500/20 bg-emerald-500/5 p-3">
                        <p class="font-semibold text-emerald-300">{{ ucfirst($sale->sale_type) }} - {{ $sale->sale_date->format('M d, Y') }}</p>
                        <p class="text-sm text-slate-300">{{ $sale->number_sold }} units | {{ number_format($sale->total_revenue, 0) }}</p>
                        <p class="text-xs text-slate-400">{{ $sale->buyer_name ?? __('messages.unknown_buyer') }}</p>
                    </div>
                    @empty
                    <p class="text-slate-400">{{ __('messages.no_sales_records') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
