<?php

use JeffersonGoncalves\LaravelSendgrid\Facades\Sendgrid as SendgridFacade;
use JeffersonGoncalves\LaravelSendgrid\Sendgrid;

it('registers the sendgrid singleton', function () {
    expect(app(Sendgrid::class))->toBeInstanceOf(Sendgrid::class);
});

it('resolves the facade to the sendgrid class', function () {
    expect(SendgridFacade::getFacadeRoot())->toBeInstanceOf(Sendgrid::class);
});

it('merges the config file', function () {
    expect(config('sendgrid.base_url'))->toBe('https://api.sendgrid.com/v3');
});
