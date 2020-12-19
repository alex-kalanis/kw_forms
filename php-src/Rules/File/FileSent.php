<?php

namespace kalanis\kw_forms\Rules\File;


use kalanis\kw_forms\Interfaces\IValidateFile;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class FileSent
 * @package kalanis\kw_forms\Rules\File
 * Check if input file has been sent
 */
class FileSent extends AFileRule
{
    public function validate(IValidateFile $entry): void
    {
        if (UPLOAD_ERR_NO_FILE === $entry->getError()) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }
}