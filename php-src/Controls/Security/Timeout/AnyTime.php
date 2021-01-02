<?php

namespace kalanis\kw_forms\Controls\Security\Timeout;


use kalanis\kw_forms\Interfaces;


/**
 * Class AnyTime
 * @package kalanis\kw_forms\Controls\Security\Timeout
 * Pass everytime, no rules triggered
 */
class AnyTime implements Interfaces\Timeout
{
    public function updateExpire()
    {
    }

    public function isRunning()
    {
        return true;
    }
}
