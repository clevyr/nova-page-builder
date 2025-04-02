<?php

declare(strict_types=1);

use function Pest\Laravel\withHeaders;

it('sets locale sets session and redirects', function (string $referer) {
    $locale = array_key_first(config('nova-page-builder.locales'));

    withHeaders(['Referer' => $referer])->get(route('set-locale', $locale))
        ->assertSessionHas('locale', $locale)
        ->assertRedirect($referer);
})->with([
    '/',
    fn () => config('app.url')
]);
