<?php

namespace kalanis\kw_forms\Rules\File;


use kalanis\kw_forms\Interfaces\IValidateFile;
use kalanis\kw_forms\Exceptions\RuleException;
use kalanis\kw_forms\Rules\TRule;


/**
 * Class AFileRule
 * @package kalanis\kw_forms\Rules\File
 * Abstract for checking files - must be extra due need of file-specific attributes
 */
abstract class AFileRule
{
    use TRule;

    /**
     * @param IValidateFile $entry
     * @return void
     * @throws RuleException
     */
    abstract public function validate(IValidateFile $entry): void;
}