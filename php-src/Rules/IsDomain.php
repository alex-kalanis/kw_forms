<?php

namespace kalanis\kw_forms\Rules;


/**
 * Class IsDomain
 * @package kalanis\kw_forms\Rules
 * Check if input is domain
 */
class IsDomain extends MatchesPattern
{
    protected function checkValue($againstValue)
    {
        return '/^([0-9a-z][-]?){0,63}[.][a-z]{2,6}$/'; # simple domain regex
    }
}