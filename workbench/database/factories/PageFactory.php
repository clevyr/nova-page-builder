<?php

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Workbench\App\Models\Page;

/**
 * @template TModel of Page
 *
 * @extends Factory<TModel>
 */
class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'slug' => fake()->slug(),
            'template' => 'default',
            'is_published' => true,
            'content' => fake()->paragraph(),
            'meta_keywords' => implode(',', $this->faker->words()),
            'meta_title' => fake()->sentence(),
            'meta_description' => fake()->paragraph(),
            'og_image' => fake()->imageUrl(),
        ];
    }

    public function published(bool $state): PageFactory
    {
        return $this->state(fn () => $state);
    }
}
