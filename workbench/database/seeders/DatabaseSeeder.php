<?php

declare(strict_types=1);

namespace Workbench\Database\Seeders;

use Illuminate\Database\Seeder;
use Outl1ne\MenuBuilder\MenuItemTypes\MenuItemStaticURLType;
use Outl1ne\MenuBuilder\MenuItemTypes\MenuItemTextType;
use Outl1ne\MenuBuilder\Models\Menu;
use Outl1ne\MenuBuilder\Models\MenuItem;
use Workbench\Database\Factories\PageFactory;
use Workbench\Database\Factories\UserFactory;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        UserFactory::new()->create([
            'email' => 'test@example.com',
        ]);

        UserFactory::new()->times(2)->create();

        PageFactory::new()->create();

        $menu = Menu::create([
            'name' => 'Home',
            'slug' => 'home',
        ]);

        MenuItem::create([
            'menu_id' => $menu->getKey(),
            'enabled' => true,
            'class' => MenuItemStaticURLType::class,
            'value' => '#',
            'locale' => 'en_US',
            'order' => 0,
        ]);
    }
}
