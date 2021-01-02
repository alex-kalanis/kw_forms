<?php

namespace kalanis\kw_forms\Interfaces;


use kalanis\kw_forms\Rules\ARule;


/**
 * Interface IRuleFactory
 * @package kalanis\kw_forms\Interfaces
 * Which rules are available for that class
 */
interface IRuleFactory
{
    /**
     * Get rule based on its name
     * @param string $ruleName
     * @return ARule
     */
    public function getRule(string $ruleName): ARule;
}
