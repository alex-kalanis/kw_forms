<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class LowerThan
 * @package kalanis\kw_forms\Rules
 * Check if input is lower than expected value
 */
class LowerThan extends ARule
{
    use TCheckInt;

    public function validate(IValidate $entry): void
    {
        if (intval($entry->getValue()) >= $this->againstValue) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }
}