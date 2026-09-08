<?php

namespace JeffersonGoncalves\LaravelSendgrid;

use JeffersonGoncalves\LaravelSendgrid\Resources\Bounces;
use JeffersonGoncalves\LaravelSendgrid\Resources\Campaigns;
use JeffersonGoncalves\LaravelSendgrid\Resources\Contacts;
use JeffersonGoncalves\LaravelSendgrid\Resources\SpamReports;
use JeffersonGoncalves\LaravelSendgrid\Resources\Stats;

/**
 * Entry point exposing mail sending plus one resource per SendGrid Web API
 * v3 group (https://docs.sendgrid.com/api-reference).
 */
class Sendgrid
{
    protected SendgridClient $client;

    public function __construct(string $apiKey, string $baseUrl = 'https://api.sendgrid.com/v3')
    {
        $this->client = new SendgridClient($apiKey, $baseUrl);
    }

    /**
     * Send a transactional email via POST /mail/send.
     *
     * @param  array{
     *     to: string|array<int, string>,
     *     from?: string,
     *     from_name?: string,
     *     subject?: string,
     *     text?: string,
     *     html?: string,
     *     template_id?: string,
     *     dynamic_template_data?: array<string, mixed>,
     *     cc?: string|array<int, string>,
     *     bcc?: string|array<int, string>,
     *     reply_to?: string,
     * }  $message
     */
    public function send(array $message): array
    {
        $personalization = ['to' => $this->addresses($message['to'])];

        if (isset($message['cc'])) {
            $personalization['cc'] = $this->addresses($message['cc']);
        }

        if (isset($message['bcc'])) {
            $personalization['bcc'] = $this->addresses($message['bcc']);
        }

        if (isset($message['dynamic_template_data'])) {
            $personalization['dynamic_template_data'] = $message['dynamic_template_data'];
        }

        $fromEmail = $message['from'] ?? (string) config('sendgrid.from_email');
        $fromName = $message['from_name'] ?? (string) config('sendgrid.from_name');

        $payload = [
            'personalizations' => [$personalization],
            'from' => array_filter([
                'email' => $fromEmail !== '' ? $fromEmail : null,
                'name' => $fromName !== '' ? $fromName : null,
            ]),
        ];

        if (isset($message['subject'])) {
            $payload['subject'] = $message['subject'];
        }

        if (isset($message['template_id'])) {
            $payload['template_id'] = $message['template_id'];
        } else {
            $content = [];

            if (isset($message['text'])) {
                $content[] = ['type' => 'text/plain', 'value' => $message['text']];
            }

            if (isset($message['html'])) {
                $content[] = ['type' => 'text/html', 'value' => $message['html']];
            }

            if ($content !== []) {
                $payload['content'] = $content;
            }
        }

        if (isset($message['reply_to'])) {
            $payload['reply_to'] = ['email' => $message['reply_to']];
        }

        return $this->client->post('/mail/send', $payload);
    }

    public function contacts(): Contacts
    {
        return new Contacts($this->client);
    }

    public function campaigns(): Campaigns
    {
        return new Campaigns($this->client);
    }

    public function stats(): Stats
    {
        return new Stats($this->client);
    }

    public function bounces(): Bounces
    {
        return new Bounces($this->client);
    }

    public function spamReports(): SpamReports
    {
        return new SpamReports($this->client);
    }

    public function validateEmail(string $email): array
    {
        return $this->client->post('/validations/email', ['email' => $email]);
    }

    /**
     * @param  string|array<int, string>  $addresses
     * @return array<int, array{email: string}>
     */
    protected function addresses(string|array $addresses): array
    {
        return array_map(
            fn (string $email) => ['email' => trim($email)],
            is_array($addresses) ? $addresses : explode(',', $addresses),
        );
    }
}
