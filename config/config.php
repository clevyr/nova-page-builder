<?php

return [
    'resource' => \Clevyr\NovaPageBuilder\Nova\Page::class,

    'model' => \Clevyr\NovaPageBuilder\Models\Page::class,

    'views_path' => resource_path('views/vendor/nova-page-builder/pages/'),

    'locales' => config('nova-menu.locales'),
];
