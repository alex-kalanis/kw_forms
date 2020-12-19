<?php

namespace kalanis\kw_forms\Rules\External;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;
use kalanis\kw_forms\Rules\ARule;
use kalanis\kw_forms\Rules\TCheckString;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;


/**
 * Class IsTelephone
 * @package kalanis\kw_forms\Rules\External
 * Check if input is telephone for preset country
 * @link https://github.com/giggsey/libphonenumber-for-php
 */
class IsTelephone extends ARule
{
    use TCheckString;

    public function validate(IValidate $entry): void
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $number = $phoneUtil->parse($entry->getValue(), $this->againstValue);
            if ($phoneUtil->isValidNumber($number)) {
                return;
            }
            throw new RuleException($this->errorText, $entry->getKey());
        } catch (NumberParseException $e) {
            throw new RuleException($this->errorText, $entry->getKey(), $e);
        }
    }
}