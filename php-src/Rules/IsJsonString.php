<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class IsJsonString
 * @package kalanis\kw_forms\Rules
 * Check if input is JSON string
 */
class IsJsonString extends ARule
{
    public function validate(IValidate $entry): void
    {
        json_decode($entry->getValue());
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }
}