<?php

namespace Clevyr\NovaPageBuilder\Tests;

use Orchestra\Testbench\Concerns\WithWorkbench;
use Outl1ne\MenuBuilder\MenuBuilderServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use WithWorkbench;
}
