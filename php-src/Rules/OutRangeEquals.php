<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class OutRangeEquals
 * @package kalanis\kw_forms\Rules
 * Check if input is outside set range
 */
class OutRangeEquals extends ARule
{
    use TCheckRange;

    public function validate(IValidate $entry): void
    {
        $varToCheck = intval($entry->getValue());
        if ($varToCheck >= $this->againstValue[0] && $varToCheck <= $this->againstValue[1]) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }
}