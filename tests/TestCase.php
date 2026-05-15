<?php

namespace Clevyr\NovaPageBuilder\Tests;

use Orchestra\Testbench\Concerns\WithWorkbench;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use WithWorkbench;

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        // Point Inertia's testing view-finder at the workbench's Vue pages so
        // assertInertia(...->component('Default/Index')) can confirm the
        // referenced component file actually exists on disk.
        config()->set('inertia.pages.paths', [
            \Orchestra\Testbench\workbench_path('resources/js/Pages'),
        ]);
    }
}
