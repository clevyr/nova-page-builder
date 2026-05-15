<?php

namespace Workbench\App\Providers;

use Clevyr\Filemanager\FilemanagerTool;
use Illuminate\Support\Facades\Gate;
use Laravel\Fortify\Features;
use Laravel\Nova\Dashboards\Main;
use Laravel\Nova\DevTool\DevTool as Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Outl1ne\MenuBuilder\MenuBuilder;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    public function boot(): void
    {
        parent::boot();
    }

    protected function fortify(): void
    {
        Nova::fortify()
            ->features([
                Features::updatePasswords(),
            ])
            ->register();
    }

    protected function routes(): void
    {
        Nova::routes()
            ->withAuthenticationRoutes(default: true)
            ->withPasswordResetRoutes()
            ->register();
    }

    protected function gate(): void
    {
        Gate::define('viewNova', function ($user) {
            return true;
        });
    }

    protected function dashboards(): array
    {
        return [
            new Main,
        ];
    }

    public function tools(): array
    {
        return [
            new MenuBuilder,
            new FilemanagerTool,
        ];
    }

    protected function resources(): void
    {
        Nova::resourcesInWorkbench();
    }

    public function register(): void
    {
        parent::register();
    }
}
