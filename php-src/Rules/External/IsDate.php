<?php

namespace kalanis\kw_forms\Rules\External;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;
use kalanis\kw_forms\Rules\ARule;


/**
 * Class IsDate
 * @package kalanis\kw_forms\Rules\External
 * Check if input is date for preset format
 * @link http://schoolsofweb.com/how-to-check-valid-date-format-in-php/
 * Beware! It depends on PHP's parsing! Better use something else.
 */
class IsDate extends ARule
{
    public function validate(IValidate $entry): void
    {
        $dtInfo = date_parse($entry->getValue());
        if($dtInfo['warning_count'] == 0 && $dtInfo['error_count'] == 0 ){
            return;
        }
        throw new RuleException($this->errorText, $entry->getKey());
    }
}