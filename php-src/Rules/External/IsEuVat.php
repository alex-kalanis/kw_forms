<?php

namespace kalanis\kw_forms\Rules\External;


use Ddeboer\Vatin\Validator;
use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;
use kalanis\kw_forms\Rules\ARule;


/**
 * Class IsEuVat
 * @package kalanis\kw_forms\Rules\External
 * Check if input is EU VAT number for preset country
 * @link https://github.com/topics/vat-number
 * @link https://github.com/ddeboer/vatin
 */
class IsEuVat extends ARule
{
    public function validate(IValidate $entry): void
    {
        $validator = new Validator();
        if (!$validator->isValid($entry->getValue(), true)) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }
}