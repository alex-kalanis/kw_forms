<?php

namespace kalanis\kw_forms\Interfaces;


/**
 * Interface Timeout
 * @package kalanis\kw_forms\Interfaces
 *
 * Interface for info if the object can be used
 */
interface Timeout
{
    /**
     * Can use?
     * @return string
     */
    public function isRunning();

    /**
     * Update expiration
     * @return void
     */
    public function updateExpire();
}
