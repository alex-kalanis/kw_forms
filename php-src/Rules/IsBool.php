<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class IsBool
 * @package kalanis\kw_forms\Rules
 * Check if input is boolean
 */
class IsBool extends ARule
{
    public function validate(IValidate $entry): void
    {
        if (!is_bool($entry->getValue())) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }
}