<?php

namespace Clevyr\NovaPageBuilder;

use Clevyr\Filemanager\FilemanagerTool;
use Clevyr\NovaPageBuilder\Nova\Page;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Outl1ne\MenuBuilder\MenuBuilder;

class NovaPageBuilderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('NovaPageBuilder', fn() => new NovaPageBuilder());
    }

    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Load Routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Register Page Builder resource
        Nova::resources([
            config('nova-page-builder.resource', Page::class),
        ]);

        // Register 3rd part tools
        Nova::tools([
            new MenuBuilder,
            new FilemanagerTool,
        ]);

        // Publish package & vendor files
        if ($this->app->runningInConsole()) {
            /*
             * Publish configs
             */
            $this->publishes([
                __DIR__ . '/../config/nova-tinymce.php' => config_path('nova-tinymce.php'),
                __DIR__ . '/../config/nova-menu.php' => config_path('nova-menu.php'),
                __DIR__ . '/../config/nova-page-builder.php' => config_path('nova-page-builder.php')
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
