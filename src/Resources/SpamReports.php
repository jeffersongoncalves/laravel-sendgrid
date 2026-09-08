<?php

namespace JeffersonGoncalves\LaravelSendgrid\Resources;

use JeffersonGoncalves\LaravelSendgrid\SendgridClient;

class SpamReports
{
    public function __construct(
        protected SendgridClient $client,
    ) {}

    public function list(?int $startTime = null, ?int $endTime = null, ?int $limit = null): array
    {
        return $this->client->get('/suppression/spam_reports', [
            'start_time' => $startTime,
            'end_time' => $endTime,
            'limit' => $limit,
        ]);
    }
}
