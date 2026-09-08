<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\LaravelSendgrid\Facades\Sendgrid;

it('validates an email address', function () {
    Http::fake(['api.sendgrid.com/v3/validations/email' => Http::response(['result' => ['verdict' => 'Valid']])]);

    $result = Sendgrid::validateEmail('jane@example.com');

    expect($result['result']['verdict'])->toBe('Valid');
    Http::assertSent(fn ($request) => $request->data()['email'] === 'jane@example.com');
});
