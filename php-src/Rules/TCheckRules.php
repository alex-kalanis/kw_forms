<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Exceptions\RuleException;


/**
 * trait TCheckRules
 * @package kalanis\kw_forms\Rules
 * Check original values as set of rules
 */
trait TCheckRules
{
    use TRule;

    protected function checkValue($againstValue)
    {
        if (!is_array($againstValue)) {
            throw new RuleException('No array found. Need set matching rules!');
        }
        return array_map([$this, 'checkRule'], $againstValue);
    }

    /**
     * @param mixed $singleRule
     * @return ARule
     * @throws RuleException
     */
    protected function checkRule($singleRule): ARule
    {
        if (!is_object($singleRule)) {
            throw new RuleException(sprintf('Input %s is not an object.', $singleRule));
        }
        if (!$singleRule instanceof ARule) {
            throw new RuleException(sprintf('Input %s is not instance of ARule.', get_class($singleRule)));
        }
        return $singleRule;
    }
}