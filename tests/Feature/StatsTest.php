<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\LaravelSendgrid\Facades\Sendgrid;

it('gets stats for a date range', function () {
    Http::fake(['api.sendgrid.com/v3/stats*' => Http::response([['date' => '2026-01-01', 'stats' => []]])]);

    $result = Sendgrid::stats()->get('2026-01-01', '2026-01-31');

    expect($result[0]['date'])->toBe('2026-01-01');
    Http::assertSent(fn ($request) => $request['start_date'] === '2026-01-01' && $request['end_date'] === '2026-01-31');
});
