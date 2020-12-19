<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class IsFilled
 * @package kalanis\kw_forms\Rules
 * Check if input is filled
 */
class IsFilled extends ARule
{
    public function validate(IValidate $entry): void
    {
        if (empty($entry->getValue())) {
            throw new RuleException($this->errorText);
        }
    }
}