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
            // Match the canonical template name (file is `Default.php` / Vue
            // component is `Default/Index.vue`). The migration default of
            // 'default' is a legacy artifact; pages created via Nova use the
            // capitalized form.
            'template' => 'Default',
            'is_published' => true,
            // `NovaPageBuilder::catchAll()` runs `json_decode($page->content)`
            // before passing it to Inertia. Seed a realistic Flexible-style
            // payload so the workbench preview actually renders sections.
            'content' => json_encode([
                [
                    'layout' => 'hero',
                    'attributes' => [
                        'heading' => fake()->sentence(4),
                        'image' => null,
                    ],
                ],
                [
                    'layout' => 'one-column-layout',
                    'attributes' => [
                        'content' => '<p>'.fake()->paragraph().'</p>',
                    ],
                ],
            ]),
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
