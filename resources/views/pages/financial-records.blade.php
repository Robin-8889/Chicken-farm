@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ __('messages.financial_records_short') }}</h1>
                <p class="mt-2 text-slate-300">{{ __('messages.track_profitability') }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-700 hover:bg-slate-600 px-4 py-2 text-white transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                {{ __('messages.back') }}
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="rounded-lg border border-green-500/20 bg-green-500/5 p-6 backdrop-blur">
                <p class="text-sm text-slate-400">{{ __('messages.total_revenue') }}</p>
                <p class="mt-2 text-3xl font-bold text-green-400">{{ number_format($metrics['revenue'], 0) }}</p>
            </div>
            <div class="rounded-lg border border-red-500/20 bg-red-500/5 p-6 backdrop-blur">
                <p class="text-sm text-slate-400">{{ __('messages.total_expenses') }}</p>
                <p class="mt-2 text-3xl font-bold text-red-400">{{ number_format($metrics['expenses'], 0) }}</p>
            </div>
            <div class="rounded-lg border border-emerald-500/20 bg-emerald-500/5 p-6 backdrop-blur">
                <p class="text-sm text-slate-400">{{ __('messages.net_profit_loss') }}</p>
                <p class="mt-2 text-3xl font-bold {{ $metrics['profit'] >= 0 ? 'text-emerald-400' : 'text-red-400' }}">{{ number_format($metrics['profit'], 0) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <!-- Add Expense -->
            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.record_expense') }}</h2>
                <form method="POST" action="{{ route('expenses.store') }}" class="space-y-4">
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
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.expense_category') }}</label>
                        <select name="category" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                            <option value="">{{ __('messages.select_category') }}</option>
                            <option value="feed">{{ __('messages.feed') }}</option>
                            <option value="medicine">{{ __('messages.medicine') }}</option>
                            <option value="equipment">{{ __('messages.equipment') }}</option>
                            <option value="labor">{{ __('messages.labor') }}</option>
                            <option value="utilities">{{ __('messages.utilities') }}</option>
                            <option value="maintenance">{{ __('messages.maintenance') }}</option>
                            <option value="other">{{ __('messages.other') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.expense_date') }}</label>
                        <input type="date" name="expense_date" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.amount') }}</label>
                        <input type="number" name="amount" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.notes') }}</label>
                        <textarea name="notes" rows="3" class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300"></textarea>
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-orange-400 px-4 py-2 font-semibold text-slate-950 hover:bg-orange-300 transition">{{ __('messages.save_expense') }}</button>
                </form>
            </div>

            <!-- Expense Summary by Category -->
            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.expense_breakdown') }}</h2>
                <div class="space-y-3">
                    @php
                        $categories = $expenses->groupBy('category');
                        $categoryTotals = [];
                        foreach($categories as $cat => $items) {
                            $categoryTotals[$cat] = $items->sum('amount');
                        }
                        arsort($categoryTotals);
                    @endphp
                    @forelse($categoryTotals as $category => $total)
                    <div class="flex items-center justify-between">
                        <span class="text-slate-300 capitalize">{{ $category }}</span>
                        <span class="font-semibold text-red-400">{{ number_format($total, 0) }}</span>
                    </div>
                    @empty
                    <p class="text-slate-400">{{ __('messages.no_expenses_recorded') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="mt-8 rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur overflow-x-auto">
            <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.recent_expenses') }}</h2>
            <table class="w-full text-sm text-slate-300">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="text-left py-2">{{ __('messages.date') }}</th>
                        <th class="text-left py-2">{{ __('messages.expense_category') }}</th>
                        <th class="text-left py-2">{{ __('messages.amount') }}</th>
                        <th class="text-left py-2">{{ __('messages.notes') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr class="border-b border-white/10 hover:bg-white/5">
                        <td class="py-2">{{ $expense->expense_date->format('M d, Y') }}</td>
                        <td class="py-2 capitalize">{{ $expense->category }}</td>
                        <td class="py-2 font-semibold">{{ number_format($expense->amount, 0) }}</td>
                        <td class="py-2 text-xs">
                            {{ Str::limit($expense->notes, 40) }}
                            @if($expense->batch)
                                <div class="mt-1 text-[11px] text-slate-400">Batch: {{ $expense->batch->batch_id }}</div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-slate-400">{{ __('messages.no_expenses_found') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
