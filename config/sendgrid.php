<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | Generate an API key under Settings > API Keys in your SendGrid account.
    |
    */
    'api_key' => env('SENDGRID_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Default From Address
    |--------------------------------------------------------------------------
    |
    | Used as the sender when send() is called without an explicit "from".
    |
    */
    'from_email' => env('SENDGRID_FROM_EMAIL', ''),
    'from_name' => env('SENDGRID_FROM_NAME', ''),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | The SendGrid Web API v3 base URL. Only change this to point at a proxy
    | or mock server.
    |
    */
    'base_url' => env('SENDGRID_BASE_URL', 'https://api.sendgrid.com/v3'),

];
