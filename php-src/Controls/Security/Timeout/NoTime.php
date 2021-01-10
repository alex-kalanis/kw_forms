<?php

namespace kalanis\kw_forms\Controls\Security\Timeout;


use kalanis\kw_forms\Interfaces;


/**
 * Class NoTime
 * @package kalanis\kw_forms\Controls\Security\Timeout
 * Never pass, must process rules
 */
class NoTime implements Interfaces\ITimeout
{
    public function updateExpire()
    {
    }

    public function isRunning()
    {
        return false;
    }
}
