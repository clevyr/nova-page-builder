<?php

declare(strict_types=1);

use function Pest\Laravel\withHeaders;

it('sets locale sets session and redirects', function (string $referer) {
    $locale = 'en';

    withHeaders(['Referer' => $referer])->get(route('set-locale', $locale))
        ->assertSessionHas('locale', $locale)
        ->assertRedirect($referer);
})->with([
    '/',
    fn () => config('app.url')
]);
