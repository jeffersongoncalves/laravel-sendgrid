<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\LaravelSendgrid\Facades\Sendgrid;

it('sends a plain text/html email using the configured default from address', function () {
    Http::fake(['api.sendgrid.com/v3/mail/send' => Http::response('', 202)]);

    Sendgrid::send([
        'to' => 'jane@example.com',
        'subject' => 'Hello!',
        'text' => 'Hi Jane',
        'html' => '<p>Hi Jane</p>',
    ]);

    Http::assertSent(function ($request) {
        $body = $request->data();

        return $body['personalizations'][0]['to'] === [['email' => 'jane@example.com']]
            && $body['from'] === ['email' => 'from@example.com', 'name' => 'Example Sender']
            && $body['subject'] === 'Hello!'
            && $body['content'] === [
                ['type' => 'text/plain', 'value' => 'Hi Jane'],
                ['type' => 'text/html', 'value' => '<p>Hi Jane</p>'],
            ];
    });
});

it('sends to multiple recipients with cc, bcc and a custom from address', function () {
    Http::fake(['api.sendgrid.com/v3/mail/send' => Http::response('', 202)]);

    Sendgrid::send([
        'to' => ['jane@example.com', 'john@example.com'],
        'from' => 'no-reply@example.com',
        'from_name' => 'No Reply',
        'subject' => 'Hello!',
        'html' => '<p>Hi</p>',
        'cc' => 'cc@example.com',
        'bcc' => 'bcc@example.com',
        'reply_to' => 'reply@example.com',
    ]);

    Http::assertSent(function ($request) {
        $body = $request->data();
        $personalization = $body['personalizations'][0];

        return $personalization['to'] === [['email' => 'jane@example.com'], ['email' => 'john@example.com']]
            && $personalization['cc'] === [['email' => 'cc@example.com']]
            && $personalization['bcc'] === [['email' => 'bcc@example.com']]
            && $body['from'] === ['email' => 'no-reply@example.com', 'name' => 'No Reply']
            && $body['reply_to'] === ['email' => 'reply@example.com'];
    });
});

it('sends a dynamic template email', function () {
    Http::fake(['api.sendgrid.com/v3/mail/send' => Http::response('', 202)]);

    Sendgrid::send([
        'to' => 'jane@example.com',
        'template_id' => 'd-1234567890',
        'dynamic_template_data' => ['name' => 'Jane'],
    ]);

    Http::assertSent(function ($request) {
        $body = $request->data();

        return $body['template_id'] === 'd-1234567890'
            && $body['personalizations'][0]['dynamic_template_data'] === ['name' => 'Jane']
            && ! isset($body['content']);
    });
});
