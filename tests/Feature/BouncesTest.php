<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\LaravelSendgrid\Facades\Sendgrid;

it('lists bounces', function () {
    Http::fake(['api.sendgrid.com/v3/suppression/bounces*' => Http::response([['email' => 'bounced@example.com']])]);

    $result = Sendgrid::bounces()->list(limit: 50);

    expect($result[0]['email'])->toBe('bounced@example.com');
    Http::assertSent(fn ($request) => $request['limit'] === 50);
});
