<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ __('messages.app_name') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
        <main class="relative overflow-hidden">
            <div class="pointer-events-none absolute inset-0 bg-linear-to-b from-slate-950 via-slate-900 to-slate-950"></div>
            <div class="pointer-events-none absolute left-10 top-16 h-64 w-64 rounded-full bg-amber-500/20 blur-3xl"></div>
            <div class="pointer-events-none absolute right-10 top-40 h-72 w-72 rounded-full bg-emerald-500/10 blur-3xl"></div>

            <section class="relative mx-auto flex min-h-screen w-full max-w-7xl flex-col px-6 py-8 lg:px-10">
                <header class="flex flex-col gap-4 rounded-3xl border border-white/10 bg-white/5 px-6 py-5 backdrop-blur md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-300/90">{{ __('messages.app_name') }}</p>
                        <h1 class="mt-2 text-2xl font-semibold text-white md:text-4xl">{{ __('messages.manage_farm_precision') }}</h1>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('login') }}" class="rounded-full border border-white/15 bg-white/10 px-5 py-3 text-sm font-medium text-white transition hover:bg-white/15">{{ __('messages.login') }}</a>
                        <a href="{{ route('register') }}" class="rounded-full bg-amber-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-amber-300">{{ __('messages.create_account') }}</a>
                    </div>
                </header>

                <div class="grid flex-1 items-center gap-8 py-10 lg:grid-cols-[1.1fr_0.9fr]">
                    <div class="space-y-6">
                        <div class="space-y-4">
                            <h2 class="max-w-3xl text-4xl font-semibold tracking-tight text-white md:text-6xl">{{ __('messages.track_full_batch_life') }}</h2>
                            <p class="max-w-2xl text-base leading-7 text-slate-300 md:text-lg">{{ __('messages.monitor_cycle') }}</p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                                <p class="text-sm text-slate-400">{{ __('messages.growth_tracking_short') }}</p>
                                <p class="mt-2 text-xl font-semibold text-white">{{ __('messages.weekly_weight_tracking') }}</p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                                <p class="text-sm text-slate-400">{{ __('messages.egg_production_short') }}</p>
                                <p class="mt-2 text-xl font-semibold text-white">{{ __('messages.produced_broken_sold') }}</p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                                <p class="text-sm text-slate-400">{{ __('messages.feed_medicine_short') }}</p>
                                <p class="mt-2 text-xl font-semibold text-white">{{ __('messages.feed_medicine_alerts') }}</p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                                <p class="text-sm text-slate-400">{{ __('messages.financial_records_short') }}</p>
                                <p class="mt-2 text-xl font-semibold text-white">{{ __('messages.profit_loss_summary') }}</p>
                            </div>
                        </div>
                    </div>

                    <aside class="rounded-4xl border border-white/10 bg-slate-900/70 p-6 shadow-2xl shadow-black/30 backdrop-blur">
                        <h3 class="text-2xl font-semibold text-white">{{ __('messages.what_is_included') }}</h3>
                        <ul class="mt-5 space-y-3 text-sm leading-6 text-slate-200">
                            <li>{{ __('messages.dashboard_totals_notifications') }}</li>
                            <li>{{ __('messages.batch_registration_age_tracking') }}</li>
                            <li>{{ __('messages.weekly_growth_record_keeping') }}</li>
                            <li>{{ __('messages.egg_production_tracking') }}</li>
                            <li>{{ __('messages.feed_medicine_control') }}</li>
                            <li>{{ __('messages.mortality_home_consumption') }}</li>
                            <li>{{ __('messages.sales_expenses_revenue') }}</li>
                            <li>{{ __('messages.admin_worker_support') }}</li>
                        </ul>
                    </aside>
                </div>
            </section>
        </main>
    </body>
</html>