<?php

namespace LuFiipe\LaravelInseeSierene\Tests;

use Illuminate\Support\Facades\Event;
use LuFiipe\InseeSierene\Events;
use LuFiipe\InseeSierene\Request\Request as SiereneRequest;
use LuFiipe\InseeSierene\Sirene as SireneClient;
use LuFiipe\LaravelInseeSierene\Events\SireneRateLimitReachedEvent;
use LuFiipe\LaravelInseeSierene\Events\SireneRequestingEvent;
use LuFiipe\LaravelInseeSierene\Facades\Sirene as SireneFacade;
use LuFiipe\SimplEvent\Event as SimplEvent;

/**
 * INSEE Sierene ServiceProvider tester
 */
class InseeSiereneServiceProviderTest extends TestCase
{
    /**
     * @return void
     */
    public function testFacade(): void
    {
        $obj = new SireneClient();
        $this->assertEquals($obj->getVersion(), SireneFacade::getVersion());
    }

    /**
     * @return void
     */
    public function testSireneRequestingEvent(): void
    {
        $siereneRequest = new SiereneRequest('get', SireneClient::URL_SIRENE_API, '/informations');

        $actualRequest = null;
        Event::listen(function (SireneRequestingEvent $event) use (&$actualRequest) {
            $actualRequest = $event->getRequest();
        });

        SimplEvent::emit(Events::REQUESTING, $siereneRequest);

        $this->assertEquals($siereneRequest, $actualRequest);
    }

    /**
     * @return void
     */
    public function testSireneRateLimitReachedEvent(): void
    {
        $expectedMs = 1000;
        $expectedRetries = 1;

        $milliseconds = 0;
        $retries = 0;
        Event::listen(function (SireneRateLimitReachedEvent $event) use (&$milliseconds, &$retries) {
            $milliseconds = $event->getMilliseconds();
            $retries = $event->getRetries();
        });

        SimplEvent::emit(Events::RATE_LIMIT_REACHED, $expectedMs, $expectedRetries);

        $this->assertEquals($expectedMs, $milliseconds);
        $this->assertEquals($expectedRetries, $retries);
    }
}
