<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class IsString
 * @package kalanis\kw_forms\Rules
 * Check if input is string
 */
class IsString extends ARule
{
    public function validate(IValidate $entry): void
    {
        if (!is_string($entry->getValue())) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }
}