<?php

namespace Perevorot\LaravelOctober\Facades;

use Illuminate\Support\Facades\Facade;

class SEO extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'seo';
    }
}
