<?php

namespace Roumen\Feed\Facades;

use Illuminate\Support\Facades\Facade;

class Feed extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'feed';
    }
}
