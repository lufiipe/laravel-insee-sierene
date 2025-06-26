[![GitHub Release](https://img.shields.io/github/v/release/lufiipe/laravel-insee-sierene)](https://github.com/lufiipe/laravel-insee-sierene/releases)
[![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/lufiipe/laravel-insee-sierene/php_run_tests.yml)](https://github.com/lufiipe/laravel-insee-sierene/actions)
[![GitHub License](https://img.shields.io/github/license/lufiipe/laravel-insee-sierene?color=yellow)](LICENSE)

# INSEE Sirene client for Laravel

The INSEE Sirene client package is a Laravel library that provides a simple and easy-to-use interface for interacting with the INSEE API. It allows you to retrieve legal data, such as company information.

With this package, you can:

- :white_check_mark: Advanced search
- :white_check_mark: Facets
- :white_check_mark: Iterates over the items in the collection
- :white_check_mark: API Rate Limiting
- :white_check_mark: Event listener

## Prerequisites

To interact with an Insee API, you need a valid API key. You can request one by following the step-by-step instructions provided on the "[Connexion à l'API Sirene - Mode d'emploi](https://portail-api.insee.fr/catalog/api/2ba0e549-5587-3ef1-9082-99cd865de66f/doc?page=85c5657d-b1a1-4466-8565-7db1a194667b)" page. 

## Installation & setup

You can install this package via composer using:

```
composer require lufiipe/laravel-insee-sierene
```

The package will automatically register its service provider.

To publish the config file to `config\insee-sirene.php` run:

```
php artisan vendor:publish --provider="LuFiipe\LaravelInseeSierene\InseeSiereneServiceProvider" --tag=insee-sirene-config
```

This is the default contents of the configuration:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | INSEE Sirene API Key
    |--------------------------------------------------------------------------
    |
    | To interact with the INSEE APIs, you must first obtain a valid API key.
    | This key is used to authenticate your requests and must be included in
    | each call to the API. You can request an API key by registering on the
    | INSEE developer portal.
    |
    */
    'key' => '',

    /*
    |--------------------------------------------------------------------------
    | Rate limiting
    |--------------------------------------------------------------------------
    |
    | The usage of the Sirene API is limited to 30 requests per minute.
    | By setting this variable to TRUE, the library automatically manages this
    | limitation by suspending program execution temporarily when necessary.
    |
    */
    'retry_on_rate_limit' => true,

    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    |
    | Float describing the total timeout in seconds.
    | Use 0 to wait indefinitely (the default behavior).
    |
    */
    'timeout' => null,
];
```

## Basic Usage

```php
use LuFiipe\InseeSierene\Exception\SireneException;
use LuFiipe\InseeSierene\Parameters\SearchParameters;
use LuFiipe\LaravelInseeSierene\Facades\Sirene;

// Get legal entity details by SIREN number
Sirene::siren('120027016')->get();

// Get establishment details by SIRET Number
Sirene::siret('12002701600563')->get();

// Searches for legal entities whose name currently contains or previously contained the term "INSEE"
$parameters = (new SearchParameters)
    ->setQuery('periode(denominationUniteLegale:INSEE)');
$collection = Sirene::searchLegalUnits($parameters);
$collection->each(function (array $legalUnit) {
    dump($legalUnit);
});

// Retrieves establishments containing the name "WWF"
$parameters = (new SearchParameters)
    ->setQuery('denominationUniteLegale:"WWF"');
$collection = Sirene::searchEstablishments($parameters);
$collection->each(function (array $establishment) {
    dump($establishment);
});

// INSEE Sirene Service Status
try {
    $res = Sirene::informations();
} catch (SireneException $e) {
    // ../..
}
```

## Listening for events

The package fires events where you can listen for to perform some extra logic.

### \LuFiipe\LaravelInseeSierene\Events\SireneRequestingEvent

This event will fire at the very beginning of request.

It has one public method `getRequest()`, that returns an instance of `LuFiipe\InseeSierene\Request\Request`.

### \LuFiipe\LaravelInseeSierene\Events\SireneRateLimitReachedEvent`

This event will fire when the API rate limit has been reached.

It has two public methods:
 - `getMilliseconds()` : Returns the number of milliseconds to wait before the next request.
 - `getRetries()` : Returns the number of retries.

 ### Exemple

Creates a `LogSireneRequestingListener` and a `LogSireneRateLimitReachedListener` listeners:

```php
namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use LuFiipe\LaravelInseeSierene\Events\SireneRequestingEvent;

class LogSireneRequestingListener
{
    public function handle(SireneRequestingEvent $event): void
    {
        $request = $event->getRequest();

        Log::info('Insee Sirene : Query', [
            'method' => $request->getMethod(),
            'url' => $request->getUrl(),
            'params' => $request->getRequestBody(),
        ]);
    }
}
```

```php
namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use LuFiipe\LaravelInseeSierene\Events\SireneRequestingEvent;

class LogSireneRateLimitReachedListener
{
    public function handle(SireneRateLimitReachedEvent $event)
    {
        Log::info('Insee Sirene : Rate limiting', [
            'Wait ms' => $event->getMilliseconds(),
            'Retries' => $event->getretries(),
        ]);
    }
}
```

Registering the Event and Listener in the `EventServiceProvider`:

```php
protected $listen = [
    \LuFiipe\LaravelInseeSierene\Events\SireneRequestingEvent::class => [
        \App\Listeners\LogSireneRequestingListener::class,
    ],
    \LuFiipe\LaravelInseeSierene\Events\SireneRateLimitReachedEvent::class => [
        \App\Listeners\LogSireneRateLimitReachedListener::class,
    ],
];
```

This will generate a log output similar to the following:

```
[2024-11-07 17:08:50] local.INFO: Insee Sirene : Query {"method":"GET","url":"https://api.insee.fr/api-sirene/3.11/informations","params":[]} 
[2024-11-07 17:08:50] local.INFO: Insee Sirene : Rate limiting {"Wait ms":18026,"Retries":1} 
```

## Documentation

You'll find the documentation on https://lufiipe.github.io/insee-sirene-docs/.