<?php

use Illuminate\Http\Request;

Route::middleware(['web'])->group(function() {
    Route::get('/locale/{locale}', function (Request $request, $locale) {
        if (
            $request->hasSession() &&
            (key_exists($locale, config('nova-page-builder.locales')) || in_array($locale, config('nova-page-builder.locales')))
        ) {
            $request->session()->put('locale', $locale);
        }

        $referrer = request()->headers->get('referer');

        return redirect($referrer ?? '/');
    })->name('set-locale');

    Route::fallback(function () {
        return Clevyr\NovaPageBuilder\NovaPageBuilder::catchAll();
    });
});
