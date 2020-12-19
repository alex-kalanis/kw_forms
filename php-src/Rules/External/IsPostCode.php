<?php

namespace kalanis\kw_forms\Rules\External;


use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Exceptions\RuleException;
use kalanis\kw_forms\Rules\ARule;
use kalanis\kw_forms\Rules\TCheckString;


/**
 * Class IsPostCode
 * @package kalanis\kw_forms\Rules\External
 * Check if input is post code for preset country
 * @link https://gist.github.com/jamesbar2/1c677c22df8f21e869cca7e439fc3f5b
 */
class IsPostCode extends ARule
{
    use TCheckString;

    protected static $codes = [];

    public static function loadCodes(string $pathToCodes): void
    {
        $codeFile = file_get_contents($pathToCodes);
        $codes = json_decode($codeFile, true);
        static::$codes = array_combine(
            array_column($codes, 'ISO'),
            $codes
        );
    }

    public function validate(IValidate $entry): void
    {
        if (!isset(static::$codes[$this->againstValue])) {
            throw new RuleException(sprintf('Unknown preset ISO key for country %s', $this->againstValue), $entry->getKey());
        }
        $rule = static::$codes[$this->againstValue];
        if (empty($rule['Regex']) && empty($entry->getValue())) {
            return;
        }
        if (!boolval(preg_match($rule['Regex'], $entry->getValue()))) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }
}