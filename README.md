<div class="filament-hidden">

![Laravel SendGrid](https://raw.githubusercontent.com/jeffersongoncalves/laravel-sendgrid/main/art/jeffersongoncalves-laravel-sendgrid.png)

</div>

# Laravel SendGrid

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-sendgrid.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-sendgrid)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-sendgrid/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-sendgrid/actions?query=workflow%3ATests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-sendgrid/pint.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-sendgrid/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-sendgrid.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-sendgrid)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/laravel-sendgrid.svg?style=flat-square)](LICENSE.md)

A Laravel wrapper for [SendGrid's Web API v3](https://docs.sendgrid.com/api-reference). Covers transactional email sending, contact/list management, campaigns, stats and suppressions through a simple, typed API built on Laravel's `Http` client.

## Features

- Mail: `send` (plain text/HTML content, dynamic templates, cc/bcc, reply-to)
- Contacts: `list`, `add`, `search`
- Campaigns: `list`, `get`
- Stats: `get`
- Bounces: `list`
- Spam reports: `list`
- Email validation: `validateEmail`
- Throws `SendgridException` (with the original API error body) on any non-2xx response

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/laravel-sendgrid
```

Publish the config file:

```bash
php artisan vendor:publish --tag=sendgrid-config
```

Set your SendGrid API key in `.env`:

```env
SENDGRID_API_KEY=your-api-key
SENDGRID_FROM_EMAIL=no-reply@example.com
SENDGRID_FROM_NAME="Example App"
```

Generate an API key under **Settings > API Keys** in your SendGrid account.

## Configuration

```php
// config/sendgrid.php
return [
    'api_key' => env('SENDGRID_API_KEY', ''),
    'from_email' => env('SENDGRID_FROM_EMAIL', ''),
    'from_name' => env('SENDGRID_FROM_NAME', ''),
    'base_url' => env('SENDGRID_BASE_URL', 'https://api.sendgrid.com/v3'),
];
```

## Usage

The package is resolved via the `Sendgrid` facade or by injecting `JeffersonGoncalves\LaravelSendgrid\Sendgrid`. Resources are exposed as methods returning a dedicated resource class.

### Sending mail

```php
use JeffersonGoncalves\LaravelSendgrid\Facades\Sendgrid;

Sendgrid::send([
    'to' => 'jane@example.com',
    'subject' => 'Welcome!',
    'text' => 'Hi Jane, welcome aboard.',
    'html' => '<p>Hi Jane, welcome aboard.</p>',
]);

// Multiple recipients, cc/bcc, custom from, reply-to
Sendgrid::send([
    'to' => ['jane@example.com', 'john@example.com'],
    'from' => 'no-reply@example.com',
    'from_name' => 'Example App',
    'subject' => 'Welcome!',
    'html' => '<p>Welcome aboard.</p>',
    'cc' => 'cc@example.com',
    'bcc' => 'bcc@example.com',
    'reply_to' => 'support@example.com',
]);

// Dynamic templates
Sendgrid::send([
    'to' => 'jane@example.com',
    'template_id' => 'd-1234567890',
    'dynamic_template_data' => ['name' => 'Jane'],
]);
```

If `from`/`from_name` are omitted, the values from `config('sendgrid.from_email')` / `config('sendgrid.from_name')` are used.

### Contacts

```php
$contacts = Sendgrid::contacts()->list();

Sendgrid::contacts()->add(
    email: 'jane@example.com',
    firstName: 'Jane',
    lastName: 'Doe',
    listIds: ['list-id'],
);

$result = Sendgrid::contacts()->search('email LIKE \'jane%\'');
```

### Campaigns

```php
$campaigns = Sendgrid::campaigns()->list(pageSize: 10);

$campaign = Sendgrid::campaigns()->get('campaign-id');
```

### Stats

```php
$stats = Sendgrid::stats()->get(startDate: '2026-01-01', endDate: '2026-01-31');
```

### Bounces

```php
$bounces = Sendgrid::bounces()->list(limit: 50);
```

### Spam reports

```php
$reports = Sendgrid::spamReports()->list(limit: 50);
```

### Email validation

```php
$result = Sendgrid::validateEmail('jane@example.com');
```

### Error handling

Any non-2xx API response throws `JeffersonGoncalves\LaravelSendgrid\Exceptions\SendgridException`, which exposes the decoded error body:

```php
use JeffersonGoncalves\LaravelSendgrid\Exceptions\SendgridException;

try {
    Sendgrid::send(['to' => 'jane@example.com', 'subject' => 'Hi', 'text' => 'Hi']);
} catch (SendgridException $e) {
    logger()->error($e->getMessage(), $e->errorBody());
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jefferson Simão Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
