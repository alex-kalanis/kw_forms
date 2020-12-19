<?php

namespace kalanis\kw_forms\Rules\File;


use kalanis\kw_forms\Interfaces\IValidateFile;
use kalanis\kw_forms\Exceptions\RuleException;
use kalanis\kw_forms\Rules\TCheckArrayRange;


/**
 * Class ImageSizeList
 * @package kalanis\kw_forms\Rules\File
 * Check if input image size is in list of preset ones
 */
class ImageSizeList extends AFileRule
{
    use TCheckArrayRange;

    public function validate(IValidateFile $entry): void
    {
        if (!empty($filename)) {
            $imageSize = getimagesize($filename);
            if (false !== $imageSize) {
                foreach ($this->againstValue as $argument) {
                    if (($imageSize[0] == $argument[0]) && ($imageSize[1] == $argument[1])) {
                        return;
                    }
                }
            }
        }

        throw new RuleException($this->errorText, $entry->getKey());
    }
}