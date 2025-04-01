<?php

declare(strict_types=1);

use function Pest\Laravel\get;
use function Pest\Laravel\withHeaders;

it('sets locale and session and redirects to root page', function () {
    $locale = array_key_first(config('nova-page-builder.locales'));

    get(route('set-locale', $locale))
        ->assertSessionHas('locale', $locale)
        ->assertRedirect('/');
});

it('sets locale sets session and redirects to referrer', function (string $referer) {
    $locale = array_key_first(config('nova-page-builder.locales'));

    withHeaders(['Referer' => $referer])->get(route('set-locale', $locale))
        ->assertSessionHas('locale', $locale)
        ->assertRedirect($referer);
})->with([
    '/',
    'http://localhost/'
]);