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

it('falls back to the default path when the referer is a protocol-relative URL', function () {
    // `//evil.com/x` is a "network-path reference" — browsers expand it to the
    // current scheme + that host. parse_url() returns no host for this string,
    // so it can't be same-origin matched; we should reject.
    $locale = array_key_first(config('nova-menu.locales'));

    withHeaders(['Referer' => '//evil.example/phishing'])
        ->get(route('set-locale', $locale))
        ->assertSessionHas('locale', $locale)
        ->assertRedirect('/');
});

it('rejects unknown locales without writing to the session', function () {
    withHeaders(['Referer' => '/'])
        ->get(route('set-locale', 'not-a-real-locale'))
        ->assertSessionMissing('locale')
        ->assertRedirect('/');
});

