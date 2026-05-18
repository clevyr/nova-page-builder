<?php

declare(strict_types=1);

use Clevyr\Filemanager\FilemanagerTool;
use Clevyr\NovaPageBuilder\Nova\Page;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Outl1ne\MenuBuilder\MenuBuilder;

beforeEach(function () {
    Nova::$tools = [];
    Nova::$scripts = [];
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

it('overrides TinyMCE branding and promotion options at boot', function () {
    expect(config('nova-tinymce-editor.init.branding'))->toBeFalse()
        ->and(config('nova-tinymce-editor.init.promotion'))->toBeFalse();
});

it('registers a Nova script for the self-hosted TinyMCE when the asset is published', function () {
    $tinymcePath = public_path('vendor/nova-page-builder/tinymce/tinymce.min.js');
    File::ensureDirectoryExists(dirname($tinymcePath));
    File::put($tinymcePath, '// stub');

    try {
        event(new ServingNova(app(), request()));

        $names = collect(Nova::$scripts)->map(fn ($script) => $script->name());
        expect($names)->toContain('nova-page-builder-tinymce');
    } finally {
        File::delete($tinymcePath);
    }
});

it('logs a warning and skips the script registration when TinyMCE assets are missing', function () {
    $tinymcePath = public_path('vendor/nova-page-builder/tinymce/tinymce.min.js');
    File::delete($tinymcePath);

    Log::spy();

    event(new ServingNova(app(), request()));

    $names = collect(Nova::$scripts)->map(fn ($script) => $script->name());
    expect($names)->not->toContain('nova-page-builder-tinymce');
    Log::shouldHaveReceived('warning')
        ->withArgs(fn ($message) => str_contains($message, 'self-hosted TinyMCE assets not found')
            && str_contains($message, 'clevyr-nova-page-builder-tinymce'));
});
