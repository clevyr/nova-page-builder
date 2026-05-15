<?php

declare(strict_types=1);

use function Pest\Laravel\withHeaders;

it('sets locale sets session and redirects', function (string $referer) {
    $locale = array_key_first(config('nova-menu.locales'));

    withHeaders(['Referer' => $referer])->get(route('set-locale', $locale))
        ->assertSessionHas('locale', $locale)
        ->assertRedirect($referer);
})->with([
    '/',
    fn () => config('app.url'),
]);

it('falls back to the default path when the referer is a cross-origin URL', function () {
    $locale = array_key_first(config('nova-menu.locales'));

    withHeaders(['Referer' => 'https://evil.example/phishing'])
        ->get(route('set-locale', $locale))
        ->assertSessionHas('locale', $locale)
        ->assertRedirect('/');
});

