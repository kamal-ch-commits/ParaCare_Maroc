<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        abort_unless(in_array($locale, config('app.supported_locales', []), true), 404);

        $request->session()->put('locale', $locale);
        app()->setLocale($locale);

        if ($request->user() && $request->user()->preferred_language !== $locale) {
            $request->user()->forceFill([
                'preferred_language' => $locale,
            ])->save();
        }

        return redirect()->back();
    }
}
