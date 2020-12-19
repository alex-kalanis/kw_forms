<?php

namespace kalanis\kw_forms\Rules\File;


use finfo;
use kalanis\kw_forms\Interfaces\IValidateFile;
use kalanis\kw_forms\Exceptions\RuleException;
use kalanis\kw_forms\Rules\TCheckString;


/**
 * Class FileMimeEquals
 * @package kalanis\kw_forms\Rules\File
 * Check if input file has correct mime type
 */
class FileMimeEquals extends AFileRule
{
    use TCheckString;

    public function validate(IValidateFile $entry): void
    {
        $filename = $entry->getTempName();
        $finfo =  new finfo(FILEINFO_MIME_TYPE);
        if (!empty($filename) && $finfo && ($finfo->file($filename) == $this->againstValue)) {
            return;
        }
        throw new RuleException($this->errorText, $entry->getKey());
    }
}