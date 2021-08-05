<?php

namespace Clevyr\NovaPageBuilder\Facades;

use Illuminate\Support\Facades\Facade;

class NovaPageBuilder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'NovaPageBuilder';
    }
}