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
                    <div class="rounded-lg border border-blue-500/20 bg-blue-500/5 p-3">
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
</div>
@endsection
