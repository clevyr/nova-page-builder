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

    // Send an Inertia XHR request — exercises the data-response path through
    // catchAll() without depending on a root Blade view (which is the consumer's
    // responsibility, not this package's). A non-Inertia GET would also need
    // resources/views/app.blade.php, which is out of scope here.
    $response = $this->get('/about', [
        'X-Inertia' => 'true',
        'X-Inertia-Version' => '1',
    ]);

    expect($response->status())->toBe(200)
        ->and($response->headers->get('X-Inertia'))->toBe('true');
});
