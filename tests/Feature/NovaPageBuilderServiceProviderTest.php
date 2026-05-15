<?php

declare(strict_types=1);

use Clevyr\NovaPageBuilder\Nova\Page;
use Laravel\Nova\Nova;

it('registers the Page resource with Nova', function () {
    expect(Nova::$resources)->toContain(Page::class);
});
