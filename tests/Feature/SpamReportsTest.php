<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\LaravelSendgrid\Facades\Sendgrid;

it('lists spam reports', function () {
    Http::fake(['api.sendgrid.com/v3/suppression/spam_reports*' => Http::response([['email' => 'spam@example.com']])]);

    $result = Sendgrid::spamReports()->list(limit: 25);

    expect($result[0]['email'])->toBe('spam@example.com');
    Http::assertSent(fn ($request) => $request['limit'] === 25);
});
