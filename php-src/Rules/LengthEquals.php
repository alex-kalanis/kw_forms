<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class LengthEquals
 * @package kalanis\kw_forms\Rules
 * Check if input length equals expected value
 */
class LengthEquals extends ARule
{
    use TCheckInt;

    public function validate(IValidate $entry): void
    {
        if (mb_strlen($entry->getValue()) == $this->againstValue) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }
}