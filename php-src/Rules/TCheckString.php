<?php

namespace kalanis\kw_forms\Rules;


/**
 * trait TCheckString
 * @package kalanis\kw_forms\Rules
 * Check original value as string
 */
trait TCheckString
{
    use TRule;

    protected function checkValue($againstValue)
    {
        return strval($againstValue);
    }
}