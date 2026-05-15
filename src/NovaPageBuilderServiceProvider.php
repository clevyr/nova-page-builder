<?php

namespace Clevyr\NovaPageBuilder;

use Clevyr\NovaPageBuilder\Nova\Page;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;

class NovaPageBuilderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('NovaPageBuilder', fn () => new NovaPageBuilder);
    }

    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Load Routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        Nova::resources([
            config('nova-page-builder.resource', Page::class),
        ]);

        // Publish package & vendor files
        if ($this->app->runningInConsole()) {
            /*
             * Publish configs
             */
            $this->publishes([
                __DIR__.'/../config/nova-menu.php' => config_path('nova-menu.php'),
                __DIR__.'/../config/nova-page-builder.php' => config_path('nova-page-builder.php'),
            ], 'clevyr-nova-page-builder');

            /*
             * Publishing the default view templates
             */
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/nova-page-builder'),
            ], 'clevyr-nova-page-builder');

            /*
             * Publish JS
             */
            $this->publishes([
                __DIR__.'/../resources/js' => resource_path('js'),
            ], 'clevyr-nova-page-builder');
        }
    }
}
