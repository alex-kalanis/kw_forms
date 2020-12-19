<?php

namespace kalanis\kw_forms\Rules\File;


use finfo;
use kalanis\kw_forms\Interfaces\IValidateFile;
use kalanis\kw_forms\Exceptions\RuleException;
use kalanis\kw_forms\Rules\TCheckArrayString;


/**
 * Class FileMimeList
 * @package kalanis\kw_forms\Rules\File
 * Check if input file has correct mime type
 */
class FileMimeList extends AFileRule
{
    use TCheckArrayString;

    public function validate(IValidateFile $entry): void
    {
        $filename = $entry->getTempName();
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (!empty($filename) && $finfo) {
            foreach ($this->againstValue as $argumentValue) {
                if ($finfo->file($filename) == $argumentValue) {
                    return;
                }
            }
        }
        throw new RuleException($this->errorText, $entry->getKey());
    }
}