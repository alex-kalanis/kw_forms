<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class UrlExists
 * @package kalanis\kw_forms\Rules
 * Check if input is url and exists
 * Call external server!!
 */
class UrlExists extends ARule
{
    public function validate(IValidate $entry): void
    {
        $headers = @get_headers($entry->getValue());
        if (!empty($headers) && (false !== strpos($headers[0], '200') )) {
            return;
        }
        throw new RuleException($this->errorText, $entry->getKey());
    }
}