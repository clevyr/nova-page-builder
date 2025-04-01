<?php

use Clevyr\NovaPageBuilder\Http\Controllers\LocaleController;

Route::middleware(['web'])->group(function () {
    Route::get('/locale/{locale}', LocaleController::class)->name('set-locale');

    Route::fallback(fn() => Clevyr\NovaPageBuilder\NovaPageBuilder::catchAll());
});
