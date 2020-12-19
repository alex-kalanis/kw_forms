<?php

namespace kalanis\kw_forms\Rules\File;


use kalanis\kw_forms\Interfaces\IValidateFile;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class FileReceived
 * @package kalanis\kw_forms\Rules\File
 * Check if input file has been received
 */
class FileReceived extends AFileRule
{
    public function validate(IValidateFile $entry): void
    {
        if (UPLOAD_ERR_OK !== $entry->getError()) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }
}