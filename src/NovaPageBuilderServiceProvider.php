<?php

namespace Clevyr\NovaPageBuilder;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Nova\Nova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

class NovaPageBuilderServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Register routes
        Route::middleware('web')->group(__DIR__.'/../routes/routes.php');

        // Register Page Builder resource
        Nova::resources([
            config('nova-page-builder.resource', \Clevyr\NovaPageBuilder\Nova\Page::class),
        ]);

        // Register 3rd part tools
        Nova::tools([
            new \OptimistDigital\MenuBuilder\MenuBuilder,
            new \Infinety\Filemanager\FilemanagerTool,
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

            // 3rd party artisan commands
            Artisan::call('vendor:publish', [
                '--force' => true,
                '--provider' => 'Anaseqal\NovaSidebarIcons\ToolServiceProvider'
            ]);
        }
    }
}
