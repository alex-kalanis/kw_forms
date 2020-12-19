<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Exceptions\RuleException;


/**
 * trait TCheckArrayRange
 * @package kalanis\kw_forms\Rules
 * Check original values as array of ranges
 */
trait TCheckArrayRange
{
    use TRule;

    protected function checkValue($againstValue)
    {
        if (!is_array($againstValue)) {
            throw new RuleException('No array found. Need set input as array!');
        }
        return array_map([$this, 'checkRule'], $againstValue);
    }

    /**
     * @param mixed $againstValue
     * @return array
     * @throws RuleException
     */
    protected function checkRule($againstValue): array
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