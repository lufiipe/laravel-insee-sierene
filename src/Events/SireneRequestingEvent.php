<?php

namespace LuFiipe\LaravelInseeSierene\Events;

use LuFiipe\InseeSierene\Request\Request;

/**
 * Event: Sirene request started
 */
class SireneRequestingEvent
{
    /**
     * INSEE Sirene request
     *
     * @var Request
     */
    private Request $request;

    /**
     * Create a new event instance
     *
     * @param Request $request INSEE Sirene request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Returns the INSEE Sirene request
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
