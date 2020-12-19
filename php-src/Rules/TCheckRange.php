<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Exceptions\RuleException;


/**
 * trait TCheckRange
 * @package kalanis\kw_forms\Rules
 * Check original values as range
 */
trait TCheckRange
{
    use TRule;

    protected function checkValue($againstValue)
    {
        if (!is_array($againstValue)) {
            throw new RuleException('No array found. Need set both values to compare!');
        }
        $values = array_map('intval', $againstValue);
        $lower = min($values);
        $higher = max($values);
        return [$lower, $higher];
    }
}