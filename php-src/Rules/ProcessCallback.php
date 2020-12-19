<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class ProcessCallback
 * @package kalanis\kw_forms\Rules
 * Check if input is accepted by callback
 */
class ProcessCallback extends ARule
{
    /**
     * @param mixed $againstValue
     * @return array
     * @throws RuleException
     */
    protected function checkValue($againstValue)
    {
        if (!is_callable($againstValue)) {
            throw new RuleException('Not callable. Need set call which returns boolean or throws RuleException!');
        }
        return $againstValue;
    }

    public function validate(IValidate $entry): void
    {
        if (!call_user_func($this->againstValue, $entry->getValue())) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }
}