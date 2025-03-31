<?php

declare(strict_types=1);

use Clevyr\NovaPageBuilder\MenuItemTypes\PageMenuItemType;
use Workbench\Database\Factories\MenuFactory;
use Workbench\Database\Factories\MenuItemFactory;
use Workbench\Database\Factories\PageFactory;
use function Pest\Laravel\assertDatabaseMissing;

it('value has validation rules', function () {
    $rules = PageMenuItemType::getRules()['value'];

    expect($rules)->toContain('required')
        ->and($rules)->toContain('exists:'.config('nova-page-builder.model'));
});

test('menu items do not dangle if page is deleted', function () {
    $page = PageFactory::new()->create();
    $menu = MenuFactory::new()->create();
    $item = MenuItemFactory::new()->create([
        'menu_id' => $menu->getKey(),
        'value' => $page->getKey(),
        'class' => PageMenuItemType::class,
    ]);
    $item_child = MenuItemFactory::new()->create([
        'menu_id' => $menu->getKey(),
        'parent_id' => $item->getKey(),
        'value' => $page->getKey(),
        'class' => PageMenuItemType::class,
    ]);

    expect($menu->rootMenuItems()->first()->getKey())->toEqual($item->getKey());

    $page->delete();

    assertDatabaseMissing(config('nova-menu.menu_items_table_name'), [
        'id' => $item->getKey(),
    ]);
    assertDatabaseMissing(config('nova-menu.menu_items_table_name'), [
        'id' => $item_child->getKey(),
    ]);
    expect($menu->rootMenuItems())->count->toEqual(0);
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