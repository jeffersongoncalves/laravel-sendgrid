<?php

namespace JeffersonGoncalves\LaravelSendgrid\Tests;

use JeffersonGoncalves\LaravelSendgrid\SendgridServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SendgridServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('sendgrid.api_key', 'test-api-key');
        $app['config']->set('sendgrid.from_email', 'from@example.com');
        $app['config']->set('sendgrid.from_name', 'Example Sender');
        $app['config']->set('sendgrid.base_url', 'https://api.sendgrid.com/v3');
    }
}
