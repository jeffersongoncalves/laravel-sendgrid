<?php

namespace JeffersonGoncalves\LaravelSendgrid\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JeffersonGoncalves\LaravelSendgrid\Sendgrid
 */
class Sendgrid extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JeffersonGoncalves\LaravelSendgrid\Sendgrid::class;
    }
}
