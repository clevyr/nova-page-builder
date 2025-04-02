<?php

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Outl1ne\MenuBuilder\MenuItemTypes\MenuItemTextType;
use Outl1ne\MenuBuilder\Models\MenuItem;

/**
 * @template TModel of MenuItem
 *
 * @extends Factory<TModel>
 */
class MenuItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = MenuItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'menu_id' => MenuFactory::new(),
            'enabled' => true,
            'name' => fake()->word(),
            'class' => MenuItemTextType::class,
            'locale' => 'en_US',
            'order' => 0,
        ];
    }
}
