<?php

namespace kalanis\kw_forms\Rules\File;


use kalanis\kw_forms\Interfaces\IValidateFile;
use kalanis\kw_forms\Exceptions\RuleException;
use kalanis\kw_forms\Rules\TCheckRange;


/**
 * Class ImageSizeMin
 * @package kalanis\kw_forms\Rules\File
 * Check if input image size is at least as preset ones
 * Zero is for unlimited
 */
class ImageSizeMin extends AFileRule
{
    use TCheckRange;

    public function validate(IValidateFile $entry): void
    {
        if (!empty($filename)) {
            $imageSize = getimagesize($filename);
            if (false !== $imageSize) {
                if (
                    (0 === $this->againstValue[0] || $imageSize[0] >= $this->againstValue[0])
                    && (0 === $this->againstValue[1] || $imageSize[1] >= $this->againstValue[1])
                ) {
                    return;
                }
            }
        }

        throw new RuleException($this->errorText, $entry->getKey());
    }
}