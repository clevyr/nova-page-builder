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

it('renders the resolved page as an Inertia response with the expected component and props', function () {
    $page = PageFactory::new()->create([
        'slug' => 'inertia-shape',
        'is_published' => true,
        'locale' => app()->getLocale(),
        'template' => 'Default',
        'title' => 'Shape Page',
        'meta_title' => 'Meta T',
        'meta_keywords' => 'meta,kw',
        'meta_description' => 'Meta D',
        'og_image' => 'og.jpg',
        'content' => json_encode([['layout' => 'hero', 'attributes' => ['heading' => 'Hi']]]),
    ]);

    // assertInertia() reads from $response->viewData('page'), which requires a
    // Blade-rendered response. The X-Inertia XHR path returns JSON directly,
    // so we assert against the JSON body instead — same coverage of the
    // response shape, without depending on a built Vite manifest.
    $this->get('/inertia-shape', ['X-Inertia' => 'true', 'X-Inertia-Version' => '1'])
        ->assertOk()
        ->assertJsonPath('component', 'Default/Index')
        ->assertJsonPath('props.page.id', $page->id)
        ->assertJsonPath('props.page.title', 'Shape Page')
        ->assertJsonCount(1, 'props.content')
        ->assertJsonPath('props.content.0.layout', 'hero')
        ->assertJsonPath('props.metadata.meta_title', 'Meta T')
        ->assertJsonPath('props.metadata.meta_keywords', 'meta,kw')
        ->assertJsonPath('props.metadata.meta_description', 'Meta D')
        ->assertJsonPath('props.metadata.og_image', 'og.jpg');
});

it('shares the navigations array when the menus table is populated', function () {
    \Outl1ne\MenuBuilder\Models\Menu::create([
        'name' => 'Main',
        'slug' => 'main',
    ]);

    PageFactory::new()->create([
        'slug' => 'with-nav',
        'is_published' => true,
        'locale' => app()->getLocale(),
        'template' => 'Default',
        'content' => '[]',
    ]);

    $this->get('/with-nav', ['X-Inertia' => 'true', 'X-Inertia-Version' => '1'])
        ->assertOk()
        ->assertJsonStructure(['props' => ['navigations']]);
});
