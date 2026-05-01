@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ __('messages.feed_medicine_short') }}</h1>
                <p class="mt-2 text-slate-300">{{ __('messages.inventory_management') }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-700 hover:bg-slate-600 px-4 py-2 text-white transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                {{ __('messages.back') }}
            </a>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.record_supply_stock') }}</h2>
                <form method="POST" action="{{ route('stocks.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.link_to_batch_optional') }}</label>
                        <select name="chicken_batch_id" class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                            <option value="">{{ __('messages.no_batch_selected') }}</option>
                            @foreach(($batches ?? collect()) as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->batch_id }} - {{ $batch->breed_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.type') }}</label>
                        <select name="category" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                            <option value="">{{ __('messages.select_category') }}</option>
                            <option value="feed">{{ __('messages.feed') }}</option>
                            <option value="medicine">{{ __('messages.medicine') }}</option>
                            <option value="equipment">{{ __('messages.equipment') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.item_name') }}</label>
                        <input type="text" name="item_name" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300" placeholder="e.g., Starter Mash">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.purchase_date') }}</label>
                        <input type="date" name="purchase_date" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.quantity_purchased') }}</label>
                        <input type="number" name="quantity_purchased" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.unit_label') }}</label>
                        <input type="text" name="unit" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.amount') }}</label>
                        <input type="number" name="cost" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-purple-400 px-4 py-2 font-semibold text-slate-950 hover:bg-purple-300 transition">{{ __('messages.save_stock_record') }}</button>
                </form>
            </div>

            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.feed_medicine_store') }}</h2>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($stocks as $stock)
                    <div class="rounded-lg border {{ $stock->remaining_quantity <= $stock->low_stock_threshold ? 'border-red-500/20 bg-red-500/5' : 'border-purple-500/20 bg-purple-500/5' }} p-3">
                        <p class="font-semibold {{ $stock->remaining_quantity <= $stock->low_stock_threshold ? 'text-red-300' : 'text-purple-300' }}">{{ $stock->item_name }}</p>
                        <p class="text-sm text-slate-300">{{ ucfirst($stock->category) }} | {{ $stock->remaining_quantity }} {{ $stock->unit }}</p>
                        @if($stock->batch)
                        <p class="text-xs text-slate-400">{{ __('messages.batch_id') }}: {{ $stock->batch->batch_id }}</p>
                        @endif
                        <p class="text-xs text-slate-400">{{ __('messages.amount') }}: {{ number_format($stock->cost, 0) }} | {{ __('messages.expiry_date') }}: {{ $stock->expiry_date?->format('M d, Y') ?? 'N/A' }}</p>
                        @if($stock->remaining_quantity <= $stock->low_stock_threshold)
                        <p class="text-xs text-red-400 font-semibold">⚠️ Low stock warning!</p>
                        @endif
                    </div>
                    @empty
                    <p class="text-slate-400">{{ __('messages.no_stock_items_yet') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
