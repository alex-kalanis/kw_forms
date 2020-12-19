<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Exceptions\RuleException;


/**
 * trait TCheckArrayString
 * @package kalanis\kw_forms\Rules
 * Check original values as set of strings
 */
trait TCheckArrayString
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
     * @param mixed $singleRule
     * @return string
     * @throws RuleException
     */
    protected function checkRule($singleRule): string
    {
        if (!is_string($singleRule)) {
            throw new RuleException(sprintf('Input %s is not a string.', $singleRule));
        }
        return $singleRule;
    }
}