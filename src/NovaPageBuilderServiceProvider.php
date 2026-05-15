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

        // Silence TinyMCE's "Get all features" promo button and "Built with TinyMCE"
        // status-bar branding. murdercode's config already sets `branding => false`
        // but doesn't touch `promotion`, and the latter is what triggers the
        // top-right upsell button in TinyMCE 6+.
        $tinymceInit = config('nova-tinymce-editor.init', []);
        config(['nova-tinymce-editor.init' => array_merge($tinymceInit, [
            'branding' => false,
            'promotion' => false,
        ])]);

        // Auto-register the bundled tools, but only if the consumer's own
        // NovaServiceProvider::tools() callback hasn't already registered them
        // (Nova doesn't dedup tool instances — two `new MenuBuilder` calls
        // produce two sidebar entries). App providers boot before package
        // providers, so the consumer's serving callback fires first and we can
        // see what's already registered here.
        //
        // Also load the self-hosted TinyMCE script so the murdercode/nova4-tinymce-editor
        // field skips its cloud loader. Assets must be published first via
        // `php artisan vendor:publish --tag=clevyr-nova-page-builder-tinymce`.
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

            Nova::script('nova-page-builder-tinymce', asset('vendor/nova-page-builder/tinymce/tinymce.min.js'));
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

            /*
             * Publish the self-hosted TinyMCE distribution into the consumer's
             * public/ directory so the murdercode/nova4-tinymce-editor field can
             * load it via Nova::script() above without hitting cdn.tiny.cloud
             * (which requires an API key and shows a nag banner without one).
             */
            $this->publishes([
                base_path('vendor/tinymce/tinymce') => public_path('vendor/nova-page-builder/tinymce'),
            ], 'clevyr-nova-page-builder-tinymce');
        }
    }
}
