<?php

namespace Clevyr\NovaPageBuilder;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Nova\Nova;
use Illuminate\Support\Facades\Route;
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
            new \OptimistDigital\MenuBuilder\MenuBuilder,
            new \Clevyr\Filemanager\FilemanagerTool,
            new \Anaseqal\NovaSidebarIcons\NovaSidebarIcons,
        ]);

        // Publish package & vendor files
        if ($this->app->runningInConsole()) {
            // Publish configs.
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('nova-page-builder.php')
            ], 'clevyr-nova-page-builder');
            $this->publishes([
                __DIR__ . '/../config/nova-tinymce.php' => config_path('nova-tinymce.php')
            ], 'clevyr-nova-page-builder');
            $this->publishes([
                __DIR__ . '/../config/nova-menu.php' => config_path('nova-menu.php')
            ], 'clevyr-nova-page-builder');

            // Publishing the views.
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/nova-page-builder'),
            ], 'clevyr-nova-page-builder');

            //  Publishing assets.
            $this->publishes([
                __DIR__.'/../resources/js' => resource_path('js'),
            ], 'clevyr-nova-page-builder');

            //  Publishing nova icon files.
            $this->publishes([
                __DIR__.'../../../anaseqal/nova-sidebar-icons/resources/js' => resource_path('views/vendor/nova/resources'),
            ], 'nova-views');

        }
    }
}
