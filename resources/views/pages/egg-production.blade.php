@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ __('messages.egg_production_short') }}</h1>
                <p class="mt-2 text-slate-300">{{ __('messages.daily_egg_records_short') }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-700 hover:bg-slate-600 px-4 py-2 text-white transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back
            </a>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.record_growth') }}</h2>
                <form method="POST" action="{{ route('eggs.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.batch_id') }}</label>
                        <select name="chicken_batch_id" class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                            <option value="">{{ __('messages.choose_batch') }}</option>
                            @foreach($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->batch_id }} - {{ $batch->breed_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.date') }}</label>
                        <input type="date" name="record_date" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.total_eggs_today') }}</label>
                        <input type="number" name="total_eggs_produced" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.broken_eggs') }}</label>
                        <input type="number" name="broken_eggs" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.eggs_consumed_home') }}</label>
                        <input type="number" name="eggs_consumed_home" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.egg_sold') }}</label>
                        <input type="number" name="eggs_sold" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-green-400 px-4 py-2 font-semibold text-slate-950 hover:bg-green-300 transition">{{ __('messages.save_egg_record') }}</button>
                </form>
            </div>

            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.recent_records') }}</h2>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($eggRecords as $record)
                    <div class="rounded-lg border border-green-500/20 bg-green-500/5 p-3">
                        <p class="font-semibold text-green-300">{{ $record->batch->batch_id }} - {{ $record->record_date->format('M d, Y') }}</p>
                        <p class="text-sm text-slate-300">Produced: {{ $record->total_eggs_produced }} | Sold: {{ $record->eggs_sold }}</p>
                        <p class="text-xs text-slate-400">Broken: {{ $record->broken_eggs }} | Home: {{ $record->eggs_consumed_home }}</p>
                    </div>
                    @empty
                    <p class="text-slate-400">No egg records yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
