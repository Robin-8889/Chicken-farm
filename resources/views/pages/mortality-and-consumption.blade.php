@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ __('messages.mortality_consumption') }}</h1>
                <p class="mt-2 text-slate-300">{{ __('messages.track_profitability') }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-700 hover:bg-slate-600 px-4 py-2 text-white transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                {{ __('messages.back') }}
            </a>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.record_mortality') }}</h2>
                <form method="POST" action="{{ route('mortality.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.batch_id') }}</label>
                        <select name="chicken_batch_id" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
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
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.dead_chicks') }}</label>
                        <input type="number" name="number_dead" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.cause_of_death') }}</label>
                        <select name="cause_of_death" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                            <option value="">{{ __('messages.select_cause') }}</option>
                            <option value="Disease">{{ __('messages.disease') }}</option>
                            <option value="Heat stress">{{ __('messages.heat_stress') }}</option>
                            <option value="Cold stress">{{ __('messages.cold_stress') }}</option>
                            <option value="Predation">{{ __('messages.predation') }}</option>
                            <option value="Accident">{{ __('messages.accident') }}</option>
                            <option value="Unknown">{{ __('messages.unknown') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.eggs_consumed_home') }}</label>
                        <input type="number" name="number_consumed_home" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.notes') }}</label>
                        <textarea name="notes" rows="3" class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300"></textarea>
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-red-400 px-4 py-2 font-semibold text-slate-950 hover:bg-red-300 transition">{{ __('messages.save_mortality_record') }}</button>
                </form>
            </div>

            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.recent_records') }}</h2>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($mortalityRecords as $record)
                    <div class="rounded-lg border border-red-500/20 bg-red-500/5 p-3">
                        <p class="font-semibold text-red-300">{{ $record->batch->batch_id }} - {{ $record->record_date->format('M d, Y') }}</p>
                        <p class="text-sm text-slate-300">{{ __('messages.dead_chicks') }}: {{ $record->number_dead }} | {{ __('messages.eggs_consumed_home') }}: {{ $record->number_consumed_home }}</p>
                        <p class="text-xs text-slate-400">{{ __('messages.cause_of_death') }}: {{ $record->cause_of_death }}</p>
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
