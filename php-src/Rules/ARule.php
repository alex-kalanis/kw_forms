<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class ARule
 * @package kalanis\kw_forms\Rules
 * Basic abstraction for checking rules
 */
abstract class ARule
{
    use TRule;

    /**
     * @param IValidate $entry
     * @return void
     * @throws RuleException
     */
    abstract public function validate(IValidate $entry): void;
}