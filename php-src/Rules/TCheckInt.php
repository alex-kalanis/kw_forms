<?php

namespace kalanis\kw_forms\Rules;


/**
 * trait TCheckInt
 * @package kalanis\kw_forms\Rules
 * Check original value as integer
 */
trait TCheckInt
{
    use TRule;

    protected function checkValue($againstValue)
    {
        return intval($againstValue);
    }
}