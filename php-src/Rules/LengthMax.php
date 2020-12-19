<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class LengthMax
 * @package kalanis\kw_forms\Rules
 * Check if input is shorter than expected value
 */
class LengthMax extends ARule
{
    use TCheckInt;

    public function validate(IValidate $entry): void
    {
        if (mb_strlen($entry->getValue()) > $this->againstValue) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }
}