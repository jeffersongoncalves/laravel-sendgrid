<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\LaravelSendgrid\Exceptions\SendgridException;
use JeffersonGoncalves\LaravelSendgrid\SendgridClient;

it('sends authenticated requests to the configured base url', function () {
    Http::fake(['api.sendgrid.com/v3/stats*' => Http::response(['stats' => []])]);

    $client = new SendgridClient('test-key');
    $client->get('/stats', ['start_date' => '2026-01-01']);

    Http::assertSent(function ($request) {
        return $request->hasHeader('Authorization', 'Bearer test-key')
            && $request->url() === 'https://api.sendgrid.com/v3/stats?start_date=2026-01-01';
    });
});

it('throws a SendgridException with the decoded error body on failure', function () {
    Http::fake(['api.sendgrid.com/v3/stats*' => Http::response(['errors' => [['message' => 'invalid api key']]], 401)]);

    $client = new SendgridClient('bad-key');

    expect(fn () => $client->get('/stats'))
        ->toThrow(SendgridException::class, 'invalid api key');
});

it('exposes the raw error body from a thrown exception', function () {
    Http::fake(['api.sendgrid.com/v3/stats*' => Http::response(['errors' => [['message' => 'invalid api key', 'field' => null]]], 401)]);

    $client = new SendgridClient('bad-key');

    try {
        $client->get('/stats');
    } catch (SendgridException $e) {
        expect($e->errorBody())->toBe(['errors' => [['message' => 'invalid api key', 'field' => null]]]);

        return;
    }

    $this->fail('Expected SendgridException was not thrown.');
});
