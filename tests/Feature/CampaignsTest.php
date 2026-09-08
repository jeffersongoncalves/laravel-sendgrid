<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\LaravelSendgrid\Facades\Sendgrid;

it('lists campaigns', function () {
    Http::fake(['api.sendgrid.com/v3/marketing/campaigns*' => Http::response(['result' => [['id' => 1]]])]);

    $result = Sendgrid::campaigns()->list(10);

    expect($result['result'][0]['id'])->toBe(1);
    Http::assertSent(fn ($request) => $request['page_size'] === 10);
});

it('gets a single campaign', function () {
    Http::fake(['api.sendgrid.com/v3/marketing/campaigns/1' => Http::response(['id' => 1])]);

    $result = Sendgrid::campaigns()->get('1');

    expect($result['id'])->toBe(1);
});
