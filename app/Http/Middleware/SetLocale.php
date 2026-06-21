<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = config('app.supported_locales', ['fr', 'en', 'ar']);
        $defaultLocale = config('app.locale', 'fr');
        $sessionLocale = $request->session()->get('locale');
        $userLocale = $request->user()?->preferred_language;

        $locale = in_array($sessionLocale, $supportedLocales, true)
            ? $sessionLocale
            : (in_array($userLocale, $supportedLocales, true) ? $userLocale : $defaultLocale);

        app()->setLocale($locale);
        $request->session()->put('locale', $locale);

        return $next($request);
    }
}
