<?php

namespace kalanis\kw_forms\Rules\File;


use kalanis\kw_forms\Interfaces\IRuleFactory;
use kalanis\kw_forms\Interfaces\IRules;
use kalanis\kw_forms\Exceptions\RuleException;
use kalanis\kw_forms\Rules\ARule;


/**
 * Class Factory
 * @package kalanis\kw_forms\Rules\File
 * Factory for getting rules for files
 */
class Factory implements IRuleFactory
{
    protected static $map = [
        IRules::FILE_SENT               => '\kalanis\kw_forms\Rules\File\FileSent',
        IRules::FILE_RECEIVED           => '\kalanis\kw_forms\Rules\File\FileReceived',
        IRules::FILE_MAX_SIZE           => '\kalanis\kw_forms\Rules\File\FileMaxSize',
        IRules::FILE_MIMETYPE_IN_LIST   => '\kalanis\kw_forms\Rules\File\FileMimeList',
        IRules::FILE_MIMETYPE_EQUALS    => '\kalanis\kw_forms\Rules\File\FileMimeEquals',
        IRules::IS_IMAGE                => '\kalanis\kw_forms\Rules\File\ImageIs',
        IRules::IMAGE_DIMENSION_EQUALS  => '\kalanis\kw_forms\Rules\File\ImageSizeEquals',
        IRules::IMAGE_DIMENSION_IN_LIST => '\kalanis\kw_forms\Rules\File\ImageSizeList',
        IRules::IMAGE_MAX_DIMENSION     => '\kalanis\kw_forms\Rules\File\ImageSizeMax',
        IRules::IMAGE_MIN_DIMENSION     => '\kalanis\kw_forms\Rules\File\ImageSizeMin',
    ];

    /**
     * @param string $ruleName
     * @return ARule
     * @throws RuleException
     */
    public function getRule(string $ruleName): ARule
    {
        if (isset(static::$map[$ruleName])) {
            $rule = static::$map[$ruleName];
            return new $rule();
        }
        throw new RuleException(sprintf('Unknown rule %s', $ruleName));
    }
}
