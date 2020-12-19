<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class IsActiveDomain
 * @package kalanis\kw_forms\Rules
 * Check if input is active domain - makes DNS request!
 */
class IsActiveDomain extends ARule
{
    public function validate(IValidate $entry): void
    {
        if (filter_var(gethostbyname($entry->getValue()), FILTER_VALIDATE_IP)) {
            return;
        }
        throw new RuleException($this->errorText, $entry->getKey());
    }
}