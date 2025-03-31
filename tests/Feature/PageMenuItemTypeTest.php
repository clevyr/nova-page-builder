<?php

declare(strict_types=1);

use Clevyr\NovaPageBuilder\MenuItemTypes\PageMenuItemType;
use Workbench\Database\Factories\MenuFactory;
use Workbench\Database\Factories\MenuItemFactory;
use Workbench\Database\Factories\PageFactory;

it('value has validation rules', function () {
    $rules = PageMenuItemType::getRules()['value'];

    expect($rules)->toContain('required')
        ->and($rules)->toContain('exists:'.config('nova-page-builder.model'));
});

test('if value is some how null it does not break the page return', function () {
    PageFactory::new()->create();
    $menu = MenuFactory::new()->create();
    MenuItemFactory::new()->create([
        'menu_id' => $menu->getKey(),
        'value' => null,
        'class' => PageMenuItemType::class,
    ]);

    expect($menu->formatForAPI('en_US'))->toBeArray();
});