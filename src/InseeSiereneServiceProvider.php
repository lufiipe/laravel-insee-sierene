<?php

namespace LuFiipe\LaravelInseeSierene;

use Illuminate\Support\ServiceProvider;
use LuFiipe\InseeSierene\Events;
use LuFiipe\InseeSierene\InseeAbstract;
use LuFiipe\InseeSierene\Request\Request as SireneRequest;
use LuFiipe\InseeSierene\Sirene as ClientSirene;
use LuFiipe\LaravelInseeSierene\Events\SireneRateLimitReachedEvent;
use LuFiipe\LaravelInseeSierene\Events\SireneRequestingEvent;
use LuFiipe\SimplEvent\Event;


/**
 * INSEE Sierene service providers
 */
class InseeSiereneServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPublishing();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/insee-sirene.php', 'insee-sirene');

        $this->app->singleton(ClientSirene::class, function () {

            // Routes events emitted via "lufiipe/simplevent" to Laravel's native event system
            Event::on(Events::REQUESTING, function (SireneRequest $request) {
                event(new SireneRequestingEvent($request));
            });
            Event::on(Events::RATE_LIMIT_REACHED, function (int $milliseconds, int $retries) {
                event(new SireneRateLimitReachedEvent($milliseconds, $retries));
            });

            $apiKey = (string) config('insee-sirene.key', '');
            $timeout = (float) config('insee-sirene.timeout', InseeAbstract::TIME_OUT_DEFAULT);
            $useRetryOnRateLimit  = (bool) config('insee-sirene.retry_on_rate_limit', true);

            return new ClientSirene($apiKey, $useRetryOnRateLimit, $timeout);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [ClientSirene::class];
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        // Register config
        $this->publishes([
            __DIR__ . '/../config/insee-sirene.php' => config_path('insee-sirene.php'),
        ], 'insee-sirene-config');
    }
}
