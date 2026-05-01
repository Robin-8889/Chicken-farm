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
                        <input type="number" name="quantity_purchased" id="quantity_purchased" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300" oninput="updateRemaining()">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.quantity_used') }}</label>
                        <input type="number" name="quantity_used" id="quantity_used" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300" oninput="updateRemaining()" min="0">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.remaining_stock') }}</label>
                        <input type="number" name="remaining_quantity" id="remaining_quantity" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.unit_label') }}</label>
                        <input type="text" name="unit" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.expiry_date') }}</label>
                        <input type="date" name="expiry_date" class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.low_stock_threshold') }}</label>
                        <input type="number" name="low_stock_threshold" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300" min="0" value="5">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.amount') }}</label>
                        <input type="number" name="cost" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.notes') }}</label>
                        <textarea name="notes" class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300" rows="2"></textarea>
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-purple-400 px-4 py-2 font-semibold text-slate-950 hover:bg-purple-300 transition">{{ __('messages.save_stock_record') }}</button>
                </form>
            </div>

            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.feed_medicine_store') }}</h2>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($stocks as $stock)
                    @php
                        $isLow = $stock->remaining_quantity <= $stock->low_stock_threshold;
                        $categoryLabel = __('messages.' . $stock->category);
                    @endphp
                    <div
                        onclick="openStockPanel(event, this)"
                        tabindex="0"
                        role="button"
                        class="group cursor-pointer rounded-2xl border {{ $isLow ? 'border-red-500/20 bg-red-500/5' : 'border-purple-500/20 bg-purple-500/5' }} p-4 transition hover:-translate-y-0.5 hover:border-amber-300/40 hover:bg-white/10"
                        data-name="{{ $stock->item_name }}"
                        data-category="{{ $stock->category }}"
                        data-category-label="{{ $categoryLabel }}"
                        data-unit="{{ $stock->unit }}"
                        data-purchased="{{ number_format($stock->quantity_purchased, 2) }}"
                        data-used="{{ number_format($stock->quantity_used, 2) }}"
                        data-remaining="{{ number_format($stock->remaining_quantity, 2) }}"
                        data-cost="{{ number_format($stock->cost, 0) }}"
                        data-expiry="{{ $stock->expiry_date?->format('M d, Y') ?? 'N/A' }}"
                        data-threshold="{{ number_format($stock->low_stock_threshold, 2) }}"
                        data-notes="{{ e($stock->notes ?? 'No notes available.') }}"
                        data-batch="{{ $stock->batch?->batch_id ?? 'No batch selected' }}"
                        data-use-url="{{ route('stocks.use', $stock) }}"
                    >
                        <div class="flex items-start gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl {{ $stock->category === 'feed' ? 'bg-amber-400/15 text-amber-300' : ($stock->category === 'medicine' ? 'bg-cyan-400/15 text-cyan-300' : 'bg-emerald-400/15 text-emerald-300') }}">
                                @if($stock->category === 'feed')
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19h16M7 19V7l5-3 5 3v12M9 19v-4h6v4" />
                                    </svg>
                                @elseif($stock->category === 'medicine')
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 3h4M9 3h6v4H9V3Zm1 4h4l1 11a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2l1-11Zm1 4h2m-1-1v2" />
                                    </svg>
                                @else
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16v10H4V7Zm2 0v10m12-10v10M7 7l1-3h8l1 3M8 17l-1 3m10-3 1 3" />
                                    </svg>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold {{ $isLow ? 'text-red-300' : 'text-purple-300' }}">{{ $stock->item_name }}</p>
                                        <p class="text-sm text-slate-300">{{ $categoryLabel }} | {{ $stock->remaining_quantity }} {{ $stock->unit }}</p>
                                    </div>
                                    <span class="rounded-full border border-white/10 px-3 py-1 text-[11px] uppercase tracking-[0.25em] text-slate-300">{{ __('messages.tap_to_open') }}</span>
                                </div>
                                @if($stock->batch)
                                <p class="mt-2 text-xs text-slate-400">{{ __('messages.batch_id') }}: {{ $stock->batch->batch_id }}</p>
                                @endif
                                <p class="text-xs text-slate-400">{{ __('messages.amount') }}: {{ number_format($stock->cost, 0) }} | {{ __('messages.expiry_date') }}: {{ $stock->expiry_date?->format('M d, Y') ?? 'N/A' }}</p>
                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-800/80">
                                    <div class="h-full rounded-full {{ $isLow ? 'bg-red-400' : 'bg-amber-300' }}" style="width: {{ max(6, min(100, ($stock->remaining_quantity / max(1, $stock->quantity_purchased)) * 100)) }}%"></div>
                                </div>
                                @if($isLow)
                                <p class="mt-2 text-xs font-semibold text-red-400">⚠️ Low stock warning!</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-slate-400">{{ __('messages.no_stock_items_yet') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div id="stockModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur">
        <div class="max-h-[92vh] w-full max-w-4xl overflow-y-auto rounded-3xl border border-white/10 bg-slate-950 shadow-2xl">
            <div class="flex items-start justify-between border-b border-white/10 px-6 py-5">
                <div>
                    <p id="stockModalCategory" class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400"></p>
                    <h3 id="stockModalTitle" class="mt-2 text-2xl font-bold text-white"></h3>
                </div>
                <button type="button" onclick="closeStockModal()" class="rounded-full border border-white/10 px-3 py-2 text-slate-300 hover:bg-white/10 hover:text-white">✕</button>
            </div>

            <div class="grid gap-6 px-6 py-6 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="space-y-6">
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                        <div class="flex items-center gap-4">
                            <div id="stockModalIcon" class="flex h-20 w-20 items-center justify-center rounded-3xl bg-white/5 text-white"></div>
                            <div class="min-w-0 flex-1">
                                <div class="grid grid-cols-2 gap-3 text-sm sm:grid-cols-4">
                                    <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-3">
                                        <p class="text-xs text-slate-400">{{ __('messages.purchased') }}</p>
                                        <p id="stockModalPurchased" class="mt-1 text-lg font-semibold text-white"></p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-3">
                                        <p class="text-xs text-slate-400">{{ __('messages.used') }}</p>
                                        <p id="stockModalUsed" class="mt-1 text-lg font-semibold text-white"></p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-3">
                                        <p class="text-xs text-slate-400">{{ __('messages.remaining') }}</p>
                                        <p id="stockModalRemaining" class="mt-1 text-lg font-semibold text-emerald-300"></p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-3">
                                        <p class="text-xs text-slate-400">{{ __('messages.threshold') }}</p>
                                        <p id="stockModalThreshold" class="mt-1 text-lg font-semibold text-amber-300"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ __('messages.batch') }}</p>
                                <p id="stockModalBatch" class="mt-2 text-sm text-slate-200"></p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ __('messages.unit') }}</p>
                                <p id="stockModalUnit" class="mt-2 text-sm text-slate-200"></p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ __('messages.cost') }}</p>
                                <p class="mt-2 text-sm text-slate-200">{{ __('messages.amount') }}: <span id="stockModalCost"></span></p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ __('messages.expiry_date') }}</p>
                                <p id="stockModalExpiry" class="mt-2 text-sm text-slate-200"></p>
                            </div>
                        </div>
                        <div class="mt-5 rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ __('messages.notes') }}</p>
                            <p id="stockModalNotes" class="mt-2 text-sm leading-6 text-slate-200"></p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <form id="use-stock-form" method="POST" action="#" class="rounded-3xl border border-white/10 bg-white/5 p-5 space-y-4">
                        @csrf
                        <h4 class="text-xl font-semibold text-white">{{ __('messages.record_stock_usage') }}</h4>
                        <p class="text-sm text-slate-400">{{ __('messages.record_stock_usage_help') }}</p>
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">{{ __('messages.used_quantity') }}</label>
                            <input id="used_quantity" name="used_quantity" type="number" step="0.01" min="0.01" required oninput="previewUsage()" class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white focus:border-amber-300">
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                            <p class="text-sm text-slate-400">{{ __('messages.remaining_after_use') }}</p>
                            <p class="mt-1 text-2xl font-bold text-emerald-300"><span id="used_quantity_preview">0.00</span></p>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">{{ __('messages.usage_notes') }}</label>
                            <textarea id="usage_notes" name="usage_notes" rows="3" class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white focus:border-amber-300" placeholder="{{ __('messages.usage_notes_placeholder') }}"></textarea>
                        </div>
                        <button type="submit" class="w-full rounded-2xl bg-amber-400 px-4 py-3 font-semibold text-slate-950 transition hover:bg-amber-300">{{ __('messages.save_usage') }}</button>
                    </form>
                    <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-amber-400/10 via-cyan-400/10 to-emerald-400/10 p-5">
                        <h4 class="text-lg font-semibold text-white">{{ __('messages.visual_aid') }}</h4>
                        <p class="mt-2 text-sm text-slate-300">{{ __('messages.visual_aid_help') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
