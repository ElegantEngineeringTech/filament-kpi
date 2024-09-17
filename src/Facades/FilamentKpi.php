<?php

namespace Elegantly\FilamentKpi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Elegantly\FilamentKpi\FilamentKpi
 */
class FilamentKpi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Elegantly\FilamentKpi\FilamentKpi::class;
    }
}
