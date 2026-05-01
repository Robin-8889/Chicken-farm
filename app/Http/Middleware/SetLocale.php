<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowed = ['en', 'sw'];

        $locale = $request->session()->get('locale');

        if (!$locale && $request->user()?->locale) {
            $locale = $request->user()->locale;
        }

        if (!in_array($locale, $allowed, true)) {
            $locale = config('app.locale', 'en');
        }

        app()->setLocale($locale);

        if (class_exists(\Carbon\Carbon::class)) {
            \Carbon\Carbon::setLocale($locale);
        }

        return $next($request);
    }
}
