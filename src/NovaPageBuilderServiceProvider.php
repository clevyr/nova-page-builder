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

        // Auto-register the bundled tools, but only if the consumer's own
        // NovaServiceProvider::tools() callback hasn't already registered them
        // (Nova doesn't dedup tool instances — two `new MenuBuilder` calls
        // produce two sidebar entries). App providers boot before package
        // providers, so the consumer's serving callback fires first and we can
        // see what's already registered here.
        Nova::serving(function () {
            $registered = collect(Nova::registeredTools());

            $tools = [];

            if ($registered->doesntContain(fn ($tool) => $tool instanceof MenuBuilder)) {
                $tools[] = new MenuBuilder;
            }

            if ($registered->doesntContain(fn ($tool) => $tool instanceof FilemanagerTool)) {
                $tools[] = new FilemanagerTool;
            }

            if ($tools) {
                Nova::tools($tools);
            }
        });

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
