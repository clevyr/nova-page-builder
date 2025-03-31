<?php

use Illuminate\Http\Request;

Route::middleware(['web'])->group(function () {
    /**
     * Set the Locale
     */
    Route::get('/locale/{locale}', function (Request $request, $locale) {
        if (
            $request->hasSession() &&
            (array_key_exists($locale, config('nova-page-builder.locales')) || in_array($locale, config('nova-page-builder.locales')))
        ) {
            $request->session()->put('locale', $locale);
        }

        $referrer = request()->headers->get('referer');

        return redirect($referrer ?? '/');
    })->name('set-locale');

    /**
     * Try to fetch Page Builder pages when no other routes are found
     */
    Route::fallback(function () {
        return Clevyr\NovaPageBuilder\NovaPageBuilder::catchAll();
    });
});
