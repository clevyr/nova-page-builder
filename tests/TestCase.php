<?php

namespace Clevyr\NovaPageBuilder\Tests;

use Orchestra\Testbench\Concerns\WithWorkbench;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use WithWorkbench;

    protected function getPackageProviders($app): array
    {
        return [
            \Laravel\Nova\NovaServiceProvider::class,
            \Laravel\Nova\NovaCoreServiceProvider::class,
            \Workbench\App\Providers\NovaServiceProvider::class,
            \Outl1ne\MenuBuilder\MenuBuilderServiceProvider::class,
            \Clevyr\NovaPageBuilder\NovaPageBuilderServiceProvider::class,
        ];
    }
}
