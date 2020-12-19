<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class InRange
 * @package kalanis\kw_forms\Rules
 * Check if input is in set range
 */
class InRange extends ARule
{
    use TCheckRange;

    public function validate(IValidate $entry): void
    {
        $varToCheck = intval($entry->getValue());
        if ($varToCheck > $this->againstValue[0] && $varToCheck < $this->againstValue[1]) {
            return;
        }
        throw new RuleException($this->errorText, $entry->getKey());
    }
}