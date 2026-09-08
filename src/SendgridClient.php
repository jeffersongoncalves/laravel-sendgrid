<?php

namespace JeffersonGoncalves\LaravelSendgrid;

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\LaravelSendgrid\Exceptions\SendgridException;

/**
 * Thin wrapper around Laravel's Http client for the SendGrid Web API v3
 * (https://docs.sendgrid.com/api-reference), authenticated with a bearer
 * API key.
 */
class SendgridClient
{
    public function __construct(
        protected string $apiKey,
        protected string $baseUrl = 'https://api.sendgrid.com/v3',
    ) {}

    /** @param array<string, mixed> $query */
    public function get(string $path, array $query = []): array
    {
        return $this->request('get', $path, array_filter($query, fn ($value) => $value !== null));
    }

    /** @param array<string, mixed>|null $body */
    public function post(string $path, ?array $body = null): array
    {
        return $this->request('post', $path, $body);
    }

    /** @param array<string, mixed>|null $body */
    public function put(string $path, ?array $body = null): array
    {
        return $this->request('put', $path, $body);
    }

    /** @param array<string, mixed>|null $data */
    protected function request(string $method, string $path, ?array $data = null): array
    {
        $response = Http::withToken($this->apiKey)
            ->acceptJson()
            ->baseUrl($this->baseUrl)
            ->{$method}($path, $data ?? []);

        if ($response->failed()) {
            throw SendgridException::fromResponse($response);
        }

        return (array) ($response->json() ?? []);
    }
}
