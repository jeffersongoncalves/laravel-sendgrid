<?php

namespace JeffersonGoncalves\LaravelSendgrid\Resources;

use JeffersonGoncalves\LaravelSendgrid\SendgridClient;

class Stats
{
    public function __construct(
        protected SendgridClient $client,
    ) {}

    public function get(?string $startDate = null, ?string $endDate = null): array
    {
        return $this->client->get('/stats', [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }
}
