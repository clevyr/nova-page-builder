<?php

declare(strict_types=1);

use Clevyr\NovaPageBuilder\Models\Page;
use Illuminate\Support\Facades\Storage;

it('exposes the storage path as an appended attribute', function () {
    $page = new Page;

    expect($page->storagePath)->toBe(Storage::url('/'))
        ->and($page->toArray())->toHaveKey('storagePath');
});

it('guards every attribute except id', function () {
    $page = new Page;

    expect($page->getGuarded())->toBe(['id']);
});
