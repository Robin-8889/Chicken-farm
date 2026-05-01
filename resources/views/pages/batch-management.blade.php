@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Header with Back Button -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ __('messages.batch_management_title') }}</h1>
                <p class="mt-2 text-slate-300">{{ __('messages.batch_management_subtitle') }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-700 hover:bg-slate-600 px-4 py-2 text-white transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                {{ __('messages.back') }}
            </a>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <!-- Add New Batch Form -->
            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.register_new_batch') }}</h2>
                <form method="POST" action="{{ route('batches.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.batch_id') }}</label>
                        <input type="text" name="batch_id" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.chicken_type') }}</label>
                        <select name="chicken_type" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                            <option value="">Select type...</option>
                            <option value="Broiler">Broiler</option>
                            <option value="Layer">Layer</option>
                            <option value="Breeder">Breeder</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.breed_name') }}</label>
                        <input type="text" name="breed_name" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300" placeholder="e.g. Ross 308">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.number_entered') }}</label>
                        <input type="number" name="number_entered" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.date_of_arrival') }}</label>
                        <input type="date" name="date_of_arrival" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.initial_average_weight_kg') }}</label>
                        <input type="number" step="0.01" name="initial_average_weight_kg" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-300 mb-2">{{ __('messages.purchase_cost') }}</label>
                        <input type="number" name="purchase_cost" required class="w-full rounded-lg border border-white/10 bg-slate-900/80 px-4 py-2 text-white focus:border-amber-300">
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-amber-400 px-4 py-2 font-semibold text-slate-950 hover:bg-amber-300 transition">{{ __('messages.add_batch') }}</button>
                </form>
            </div>

            <!-- Active Batches List -->
            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.active_batches') }}</h2>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($activeBatches as $batch)
                    <a href="{{ route('batches.show', $batch) }}" class="block rounded-lg border border-amber-500/20 bg-amber-500/5 p-3 transition hover:border-amber-300 hover:bg-amber-500/10">
                        <p class="font-semibold text-amber-300">{{ $batch->batch_id }}</p>
                        <p class="text-sm text-slate-300">{{ $batch->breed_name }} - {{ $batch->number_entered }} birds</p>
                        <p class="text-xs text-slate-400">Since {{ $batch->date_of_arrival->format('M d, Y') }}</p>
                    </a>
                    @empty
                    <p class="text-slate-400">{{ __('messages.no_active_batches') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- All Batches Table -->
        <div class="mt-8 rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur overflow-x-auto">
            <h2 class="mb-4 text-xl font-semibold text-white">{{ __('messages.all_batches') }}</h2>
            <table class="w-full text-sm text-slate-300">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="text-left py-2">{{ __('messages.batch_id') }}</th>
                        <th class="text-left py-2">{{ __('messages.type') }}</th>
                        <th class="text-left py-2">{{ __('messages.breed') }}</th>
                        <th class="text-left py-2">{{ __('messages.count') }}</th>
                        <th class="text-left py-2">{{ __('messages.status') }}</th>
                        <th class="text-left py-2">{{ __('messages.date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $batch)
                    <tr class="border-b border-white/10 hover:bg-white/5">
                        <td class="py-2"><a href="{{ route('batches.show', $batch) }}" class="text-amber-300 hover:text-amber-200">{{ $batch->batch_id }}</a></td>
                        <td class="py-2">{{ $batch->chicken_type }}</td>
                        <td class="py-2">{{ $batch->breed_name }}</td>
                        <td class="py-2">{{ $batch->number_entered }}</td>
                        <td class="py-2"><span class="px-2 py-1 rounded text-xs {{ $batch->status === 'active' ? 'bg-green-500/20 text-green-300' : 'bg-gray-500/20 text-gray-300' }}">{{ ucfirst($batch->status) }}</span></td>
                        <td class="py-2">{{ $batch->date_of_arrival->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center text-slate-400">{{ __('messages.no_batches_found') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
