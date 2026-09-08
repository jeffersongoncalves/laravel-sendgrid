<?php

namespace JeffersonGoncalves\LaravelSendgrid\Resources;

use JeffersonGoncalves\LaravelSendgrid\SendgridClient;

class Contacts
{
    public function __construct(
        protected SendgridClient $client,
    ) {}

    public function list(): array
    {
        return $this->client->get('/marketing/contacts');
    }

    /** @param string[]|null $listIds */
    public function add(string $email, ?string $firstName = null, ?string $lastName = null, ?array $listIds = null): array
    {
        $contact = array_filter([
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ], fn ($value) => $value !== null);

        $body = ['contacts' => [$contact]];

        if ($listIds !== null) {
            $body['list_ids'] = $listIds;
        }

        return $this->client->put('/marketing/contacts', $body);
    }

    public function search(string $query): array
    {
        return $this->client->post('/marketing/contacts/search', ['query' => $query]);
    }
}
