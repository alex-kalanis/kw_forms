<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class MatchAny
 * @package kalanis\kw_forms\Rules
 * Check if input matches any subrule
 */
class MatchAny extends ARule
{
    use TCheckRules;

    public function validate(IValidate $entry): void
    {
        $last = null;
        foreach ($this->againstValue as $item) {
            /** @var ARule $item */
            try {
                $item->validate($entry);
                return; // one matched, need no more lookup
            } catch (RuleException $ex) {
                // not good, continue for any other
                $ex->setPrev($last);
                $last = $ex;
            }
        }
        throw new RuleException($this->errorText, $entry->getKey(), $last);
    }
}