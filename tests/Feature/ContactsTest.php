<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\LaravelSendgrid\Facades\Sendgrid;

it('lists contacts', function () {
    Http::fake(['api.sendgrid.com/v3/marketing/contacts' => Http::response(['result' => []])]);

    $result = Sendgrid::contacts()->list();

    expect($result)->toBe(['result' => []]);
});

it('adds a contact', function () {
    Http::fake(['api.sendgrid.com/v3/marketing/contacts' => Http::response(['job_id' => 'abc123'])]);

    $result = Sendgrid::contacts()->add('jane@example.com', 'Jane', 'Doe', ['list-1']);

    expect($result['job_id'])->toBe('abc123');
    Http::assertSent(function ($request) {
        return $request->method() === 'PUT'
            && $request->data()['contacts'][0]['email'] === 'jane@example.com'
            && $request->data()['list_ids'] === ['list-1'];
    });
});

it('searches contacts', function () {
    Http::fake(['api.sendgrid.com/v3/marketing/contacts/search' => Http::response(['result' => []])]);

    Sendgrid::contacts()->search('email LIKE "jane%"');

    Http::assertSent(fn ($request) => $request->data()['query'] === 'email LIKE "jane%"');
});
