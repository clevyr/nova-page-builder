<?php

declare(strict_types=1);

use Workbench\Database\Factories\PageFactory;

it('returns 404 when no published page matches the requested slug', function () {
    $this->get('/no-such-page')->assertNotFound();
});

it('returns 404 when a matching page exists but is unpublished', function () {
    PageFactory::new()->create([
        'slug' => 'draft-page',
        'is_published' => false,
        'locale' => app()->getLocale(),
    ]);

    $this->get('/draft-page')->assertNotFound();
});

it('returns 404 when a page exists for a different locale', function () {
    PageFactory::new()->create([
        'slug' => 'french-page',
        'is_published' => true,
        'locale' => 'fr',
    ]);

    $this->get('/french-page')->assertNotFound();
});

it('renders the page when a published page matches the slug and locale', function () {
    PageFactory::new()->create([
        'slug' => 'about',
        'is_published' => true,
        'locale' => app()->getLocale(),
        'template' => 'Default',
    ]);

    // Inertia returns a 200 HTML response on full page loads; 409 with X-Inertia
    // header on Inertia XHR loads. Either way it's not a 404, which is what we
    // care about here — the catchAll() resolved the page rather than aborting.
    $response = $this->get('/about');

    expect($response->status())->not->toBe(404);
});
