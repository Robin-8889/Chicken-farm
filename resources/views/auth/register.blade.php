<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ __('messages.create_worker_account') }} | {{ config('app.name', 'Smart Poultry Farm Management System') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
        <main class="flex min-h-screen items-center justify-center px-4 py-10">
            <div class="w-full max-w-md rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl shadow-black/30 backdrop-blur">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-300/90">{{ __('messages.app_name') }}</p>
                <h1 class="mt-3 text-3xl font-semibold text-white">{{ __('messages.create_worker_account') }}</h1>
                <p class="mt-2 text-sm text-slate-300">{{ __('messages.register_subtitle') }}</p>

                <form method="POST" action="{{ route('register.store') }}" class="mt-8 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.name') }}</label>
                        <input name="name" type="text" value="{{ old('name') }}" required class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white outline-none ring-0 focus:border-amber-300">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.email') }}</label>
                        <input name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white outline-none ring-0 focus:border-amber-300">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.password') }}</label>
                        <input name="password" type="password" required class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white outline-none ring-0 focus:border-amber-300">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm text-slate-300">{{ __('messages.confirm_password') }}</label>
                        <input name="password_confirmation" type="password" required class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white outline-none ring-0 focus:border-amber-300">
                    </div>
                    <button class="w-full rounded-2xl bg-amber-400 px-4 py-3 font-semibold text-slate-950 transition hover:bg-amber-300">{{ __('messages.create_account') }}</button>
                </form>

                <p class="mt-6 text-sm text-slate-400">{{ __('messages.already_have_account') }} <a href="{{ route('login') }}" class="font-medium text-amber-300">{{ __('messages.login') }}</a></p>
                <p class="mt-2 text-sm text-slate-400"><a href="{{ route('home') }}" class="text-slate-300">{{ __('messages.back_to_home') }}</a></p>
            </div>
        </main>
    </body>
</html>