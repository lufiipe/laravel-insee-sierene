<?php

namespace LuFiipe\LaravelInseeSierene\Events;

/**
 * Event: API rate limit exceeded
 */
class SireneRateLimitReachedEvent
{
    /**
     * Number of milliseconds to wait before the next request
     *
     * @var integer
     */
    private int $milliseconds;

    /**
     * Retry Count
     *
     * @var integer
     */
    private int $retries;

    /**
     * Create a new event instance
     *
     * @param integer $milliseconds Number of milliseconds to wait before the next request
     * @param integer $retries Retry Count
     */
    public function __construct(int $milliseconds, int $retries)
    {
        $this->milliseconds = $milliseconds;
        $this->retries = $retries;
    }

    /**
     * Returns the number of milliseconds to wait before the next request
     *
     * @return integer
     */
    public function getMilliseconds(): int
    {
        return $this->milliseconds;
    }

    /**
     * Returns the number of retries.
     *
     * @return integer
     */
    public function getRetries(): int
    {
        return $this->retries;
    }
}
