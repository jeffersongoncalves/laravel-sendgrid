<?php

namespace JeffersonGoncalves\LaravelSendgrid\Resources;

use JeffersonGoncalves\LaravelSendgrid\SendgridClient;

class Bounces
{
    public function __construct(
        protected SendgridClient $client,
    ) {}

    public function list(?int $startTime = null, ?int $endTime = null, ?int $limit = null): array
    {
        return $this->client->get('/suppression/bounces', [
            'start_time' => $startTime,
            'end_time' => $endTime,
            'limit' => $limit,
        ]);
    }
}
