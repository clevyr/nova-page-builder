<?php

declare(strict_types=1);

use Clevyr\Filemanager\FilemanagerTool;
use Clevyr\NovaPageBuilder\Nova\Page;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Outl1ne\MenuBuilder\MenuBuilder;

beforeEach(function () {
    Nova::$tools = [];
});

it('registers the Page resource with Nova', function () {
    expect(Nova::$resources)->toContain(Page::class);
});

it('auto-registers MenuBuilder and FilemanagerTool when neither is already present', function () {
    event(new ServingNova(app(), request()));

    expect(collect(Nova::registeredTools())->filter(fn ($t) => $t instanceof MenuBuilder))->toHaveCount(1)
        ->and(collect(Nova::registeredTools())->filter(fn ($t) => $t instanceof FilemanagerTool))->toHaveCount(1);
});

it('does not re-register MenuBuilder if the consuming app already added it', function () {
    Nova::tools([new MenuBuilder]);

    event(new ServingNova(app(), request()));

    expect(collect(Nova::registeredTools())->filter(fn ($t) => $t instanceof MenuBuilder))->toHaveCount(1)
        ->and(collect(Nova::registeredTools())->filter(fn ($t) => $t instanceof FilemanagerTool))->toHaveCount(1);
});

it('does not re-register FilemanagerTool if the consuming app already added it', function () {
    Nova::tools([new FilemanagerTool]);

    event(new ServingNova(app(), request()));

    expect(collect(Nova::registeredTools())->filter(fn ($t) => $t instanceof FilemanagerTool))->toHaveCount(1)
        ->and(collect(Nova::registeredTools())->filter(fn ($t) => $t instanceof MenuBuilder))->toHaveCount(1);
});

it('does not register either tool if the consuming app already added both', function () {
    Nova::tools([new MenuBuilder, new FilemanagerTool]);

    event(new ServingNova(app(), request()));

    expect(Nova::registeredTools())->toHaveCount(2);
});

it('detects subclasses when deciding whether to register MenuBuilder', function () {
    $subclass = new class extends MenuBuilder {};

    Nova::tools([$subclass]);

    event(new ServingNova(app(), request()));

    expect(collect(Nova::registeredTools())->filter(fn ($t) => $t instanceof MenuBuilder))->toHaveCount(1);
});
