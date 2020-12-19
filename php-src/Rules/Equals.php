<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class Equals
 * @package kalanis\kw_forms\Rules
 * Check if input is equal to expected value
 */
class Equals extends ARule
{
    public function validate(IValidate $entry): void
    {
        if ($entry->getValue() != $this->againstValue) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }
}