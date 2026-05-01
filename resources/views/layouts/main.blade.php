<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? config('app.name', 'Smart Poultry Farm Management System') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
        <div class="min-h-screen" style="background: radial-gradient(circle at top, rgba(245,158,11,0.15), transparent 28%), radial-gradient(circle at right, rgba(34,197,94,0.12), transparent 24%), linear-gradient(180deg, #0b1220 0%, #111827 48%, #0f172a 100%);">
            <div class="mx-auto flex min-h-screen w-full flex-row" style="max-width: 1800px;">
                <aside class="flex w-80 shrink-0 flex-col border-r border-white/10 bg-slate-950/70 px-6 py-6 backdrop-blur">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-300/90">{{ __('messages.app_name') }}</p>
                            <h1 class="mt-2 text-2xl font-semibold text-white">{{ __('messages.farm_console') }}</h1>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('locale.switch', ['lang' => 'en']) }}" class="rounded-full border border-white/10 px-3 py-1 text-sm text-slate-200">{{ __('messages.english') }}</a>
                            <a href="{{ route('locale.switch', ['lang' => 'sw']) }}" class="rounded-full border border-white/10 px-3 py-1 text-sm text-slate-200">{{ __('messages.swahili') }}</a>
                        </div>
                    </div>

                    <nav class="mt-8 grid gap-2 text-sm">
                        <a href="{{ route('dashboard') }}" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-slate-100 transition hover:bg-white/10">{{ __('messages.dashboard') }}</a>
                        <a href="{{ route('batch-management') }}" class="rounded-2xl border border-white/10 px-4 py-3 text-slate-300 transition hover:bg-white/10 hover:text-white">{{ __('messages.batch_management') }}</a>
                        <a href="{{ route('growth-tracking') }}" class="rounded-2xl border border-white/10 px-4 py-3 text-slate-300 transition hover:bg-white/10 hover:text-white">{{ __('messages.growth_tracking') }}</a>
                        <a href="{{ route('egg-production') }}" class="rounded-2xl border border-white/10 px-4 py-3 text-slate-300 transition hover:bg-white/10 hover:text-white">{{ __('messages.egg_production') }}</a>
                        <a href="{{ route('feed-medicine') }}" class="rounded-2xl border border-white/10 px-4 py-3 text-slate-300 transition hover:bg-white/10 hover:text-white">{{ __('messages.feed_medicine') }}</a>
                        <a href="{{ route('mortality-consumption') }}" class="rounded-2xl border border-white/10 px-4 py-3 text-slate-300 transition hover:bg-white/10 hover:text-white">{{ __('messages.mortality_consumption') }}</a>
                        <a href="{{ route('sales-page') }}" class="rounded-2xl border border-white/10 px-4 py-3 text-slate-300 transition hover:bg-white/10 hover:text-white">{{ __('messages.sales') }}</a>
                        <a href="{{ route('financial-records') }}" class="rounded-2xl border border-white/10 px-4 py-3 text-slate-300 transition hover:bg-white/10 hover:text-white">{{ __('messages.financial_records') }}</a>
                        @if(auth()->user()?->role === 'admin')
                            <a href="{{ route('dashboard.overview') }}" class="rounded-2xl border border-white/10 px-4 py-3 text-slate-300 transition hover:bg-white/10 hover:text-white">Admin Panel</a>
                        @endif
                    </nav>

                    <div class="mt-8 rounded-3xl border border-amber-400/20 bg-amber-400/10 p-4 text-sm text-amber-100">
                        <p class="font-semibold text-white">{{ __('messages.logged_in_as') }}</p>
                        <p class="mt-1">{{ auth()->user()?->name }}</p>
                        <p class="text-amber-200/80">{{ auth()->user()?->role }}</p>
                    </div>

                    <div class="mt-auto pt-6">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full rounded-2xl border border-white/10 px-4 py-3 text-sm text-slate-200 transition hover:bg-white/10">{{ __('messages.logout') }}</button>
                        </form>
                    </div>

                    
                </aside>

                <main class="min-w-0 flex-1 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                    @if(session('status'))
                        <div class="mb-6 rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-100">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 rounded-2xl border border-rose-400/30 bg-rose-400/10 px-4 py-3 text-sm text-rose-100">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>

            <!-- Global language switcher (top-right fixed) -->
            <div class="fixed top-4 right-6 z-50 flex items-center gap-2">
                <a href="{{ route('locale.switch', ['lang' => 'en']) }}" class="rounded-full border border-white/10 px-3 py-1 text-xs text-slate-200">{{ __('messages.english') }}</a>
                <a href="{{ route('locale.switch', ['lang' => 'sw']) }}" class="rounded-full border border-white/10 px-3 py-1 text-xs text-slate-200">{{ __('messages.swahili') }}</a>
            </div>
        </div>
    </body>
    <script>
        // Client-side navigation: load page content into the <main> area on sidebar clicks
        (function(){
            const navSelector = 'aside nav a';
            const mainEl = () => document.querySelector('main');

            async function loadIntoMain(url, addHistory = true){
                try{
                    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    if(!res.ok) throw new Error('Request failed: ' + res.status);
                    const text = await res.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(text, 'text/html');
                    const newMain = doc.querySelector('main');
                    if(!newMain) {
                        // fallback: replace body if main not found
                        document.body.innerHTML = text;
                        return;
                    }
                    // replace the current main contents
                    const target = mainEl();
                    if(target){
                        target.innerHTML = newMain.innerHTML;
                        // update page title if present
                        const newTitle = doc.querySelector('title');
                        if(newTitle) document.title = newTitle.textContent;
                    }
                    if(addHistory) history.pushState({ url }, '', url);
                } catch(err){
                    console.error('Navigation error:', err);
                    // on error, fall back to full navigation
                    window.location.href = url;
                }
            }

            document.addEventListener('click', function(e){
                const a = e.target.closest(navSelector);
                if(!a) return;
                // ignore external links or anchors
                const href = a.getAttribute('href');
                if(!href || href.startsWith('http') || href.startsWith('mailto:') || href.startsWith('#')) return;

                // ignore logout form links or non-GET actions
                if(a.closest('form')) return;

                e.preventDefault();
                loadIntoMain(href, true);
            }, { passive: false });

            // handle back/forward
            window.addEventListener('popstate', function(ev){
                const state = ev.state;
                if(state && state.url){
                    loadIntoMain(state.url, false);
                } else {
                    // reload full page when no state
                    window.location.reload();
                }
            });
        })();
    </script>
</html>