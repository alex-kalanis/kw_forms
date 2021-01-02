<?php

namespace kalanis\kw_forms\Rules\File;


use kalanis\kw_forms\Interfaces\IValidateFile;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class FileExists
 * @package kalanis\kw_forms\Rules\File
 * Check if input file exists
 */
class FileExists extends AFileRule
{
    public function validate(IValidateFile $entry): void
    {
        $filename = $entry->getTempName();
        if (!empty($filename)) {
            if (is_file($filename)) {
                return;
            }
        }
        throw new RuleException($this->errorText, $entry->getKey());
    }
}
