<?php

return [
    'resource' => \Clevyr\NovaPageBuilder\Nova\Page::class,

    'model' => \Clevyr\NovaPageBuilder\Models\Page::class,

    'views_path' => \Orchestra\Testbench\workbench_path('resources/views/pages'),

    'locales' => config('nova-menu.locales'),
];
