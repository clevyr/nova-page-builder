<?php

return [
    'resource' => \Clevyr\NovaPageBuilder\Nova\Page::class,

    'controller' => \Clevyr\NovaPageBuilder\Http\Controllers\PageController::class,

    'model' => \Clevyr\NovaPageBuilder\Models\Page::class,

    'views_path' => resource_path('views/vendor/nova-page-builder/pages/'),

    'excluded_routes' => 'nova|dashboard|nova|jetstream|telescope|billing|user|register|login|forgot-password|teams|reset-password|admin'
];
