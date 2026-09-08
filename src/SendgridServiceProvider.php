<?php

namespace JeffersonGoncalves\LaravelSendgrid;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SendgridServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('sendgrid')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(Sendgrid::class, function () {
            return new Sendgrid(
                (string) config('sendgrid.api_key'),
                (string) config('sendgrid.base_url', 'https://api.sendgrid.com/v3'),
            );
        });
    }
}
