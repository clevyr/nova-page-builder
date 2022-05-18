<?php

namespace Clevyr\NovaPageBuilder;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Illuminate\Support\Facades\Artisan;

class NovaPageBuilderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('NovaPageBuilder', function($app) {
            return new NovaPageBuilder();
        });
    }

    public function boot()
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Load Routes
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        // Register Page Builder resource
        Nova::resources([
            config('nova-page-builder.resource', \Clevyr\NovaPageBuilder\Nova\Page::class),
        ]);

        // Register 3rd part tools
        Nova::tools([
            new \Outl1ne\MenuBuilder\MenuBuilder,
            new \Clevyr\Filemanager\FilemanagerTool,
        ]);

        // Publish package & vendor files
        if ($this->app->runningInConsole()) {
            /*
             * Publish configs
             */
            $this->publishes([
                __DIR__ . '/../config/nova-tinymce.php' => config_path('nova-tinymce.php'),
                __DIR__ . '/../config/nova-menu.php' => config_path('nova-menu.php'),
                __DIR__ . '/../config/config.php' => config_path('nova-page-builder.php')
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
