<?php

namespace JeffersonGoncalves\LaravelSendgrid\Resources;

use JeffersonGoncalves\LaravelSendgrid\SendgridClient;

class Campaigns
{
    public function __construct(
        protected SendgridClient $client,
    ) {}

    public function list(?int $pageSize = null): array
    {
        return $this->client->get('/marketing/campaigns', [
            'page_size' => $pageSize,
        ]);
    }

    public function get(string $id): array
    {
        return $this->client->get("/marketing/campaigns/{$id}");
    }
}
