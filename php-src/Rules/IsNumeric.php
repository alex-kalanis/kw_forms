<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class IsNumeric
 * @package kalanis\kw_forms\Rules
 * Check if input is numeric
 */
class IsNumeric extends ARule
{
    public function validate(IValidate $entry): void
    {
        if (!is_numeric($entry->getValue())) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }
}