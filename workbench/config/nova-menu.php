<?php

declare(strict_types=1);

$config = require __DIR__ . '/../../config/nova-menu.php';

return array_merge($config, [
    'menu_item_types' => [
        \Outl1ne\MenuBuilder\MenuItemTypes\MenuItemTextType::class,
        \Outl1ne\MenuBuilder\MenuItemTypes\MenuItemStaticURLType::class,
        Clevyr\NovaPageBuilder\MenuItemTypes\PageMenuItemType::class,
    ]
]);