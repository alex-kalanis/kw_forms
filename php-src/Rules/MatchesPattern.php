<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class MatchesPattern
 * @package kalanis\kw_forms\Rules
 * Check if input matches pattern
 */
class MatchesPattern extends ARule
{
    public function validate(IValidate $entry): void
    {
        if (!boolval(preg_match($this->againstValue, $entry->getValue()))) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }
}