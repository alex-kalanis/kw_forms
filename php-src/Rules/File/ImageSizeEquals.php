<?php

namespace kalanis\kw_forms\Rules\File;


use kalanis\kw_forms\Interfaces\IValidateFile;
use kalanis\kw_forms\Exceptions\RuleException;
use kalanis\kw_forms\Rules\TCheckRange;


/**
 * Class ImageSizeEquals
 * @package kalanis\kw_forms\Rules\File
 * Check if input image size equals preset ones
 */
class ImageSizeEquals extends AFileRule
{
    use TCheckRange;

    public function validate(IValidateFile $entry): void
    {
        if (!empty($filename)) {
            $imageSize = getimagesize($filename);
            if (false !== $imageSize) {
                if (($imageSize[0] == $this->againstValue[0]) && ($imageSize[1] == $this->againstValue[1])) {
                    return;
                }
            }
        }
        throw new RuleException($this->errorText, $entry->getKey());
    }
}