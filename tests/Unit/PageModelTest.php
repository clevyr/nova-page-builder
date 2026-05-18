<?php

declare(strict_types=1);

use Clevyr\NovaPageBuilder\Models\Page;
use Illuminate\Support\Facades\Storage;

it('exposes the storage path as an appended attribute', function () {
    $page = new Page;

    expect($page->storagePath)->toBe(Storage::url('/'))
        ->and($page->toArray())->toHaveKey('storagePath');
});

it('ignores id when mass-assigned because id is guarded', function () {
    $page = new Page;
    $page->fill(['id' => 999, 'title' => 'Mass Assigned']);

    expect($page->id)->toBeNull()
        ->and($page->title)->toBe('Mass Assigned');
});
