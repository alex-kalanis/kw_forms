<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Interfaces\IRuleFactory;
use kalanis\kw_forms\Interfaces\IRules;
use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Class Factory
 * @package kalanis\kw_forms\Rules
 * Factory for getting rules
 */
class Factory implements IRuleFactory
{
    protected static $map = [
        IRules::MATCH_ALL              => '\kalanis\kw_forms\Rules\MatchAll',
        IRules::MATCH_ANY              => '\kalanis\kw_forms\Rules\MatchAny',
        IRules::EQUALS                 => '\kalanis\kw_forms\Rules\Equals',
        IRules::NOT_EQUALS             => '\kalanis\kw_forms\Rules\NotEquals',
        IRules::IS_GREATER_THAN        => '\kalanis\kw_forms\Rules\GreaterThan',
        IRules::IS_LOWER_THAN          => '\kalanis\kw_forms\Rules\LowerThan',
        IRules::IS_GREATER_THAN_EQUALS => '\kalanis\kw_forms\Rules\GreaterEquals',
        IRules::IS_LOWER_THAN_EQUALS   => '\kalanis\kw_forms\Rules\LowerEquals',
        IRules::IS_NUMERIC             => '\kalanis\kw_forms\Rules\IsNumeric',
        IRules::IS_STRING              => '\kalanis\kw_forms\Rules\IsString',
        IRules::IS_BOOL                => '\kalanis\kw_forms\Rules\IsBool',
        IRules::MATCHES_PATTERN        => '\kalanis\kw_forms\Rules\MatchesPattern',
        IRules::LENGTH_MIN             => '\kalanis\kw_forms\Rules\LengthMin',
        IRules::LENGTH_MAX             => '\kalanis\kw_forms\Rules\LengthMax',
        IRules::LENGTH_EQUALS          => '\kalanis\kw_forms\Rules\LengthEquals',
        IRules::IN_RANGE               => '\kalanis\kw_forms\Rules\InRange',
        IRules::IN_RANGE_EQUALS        => '\kalanis\kw_forms\Rules\InRangeEquals',
        IRules::NOT_IN_RANGE           => '\kalanis\kw_forms\Rules\OutRange',
        IRules::NOT_IN_RANGE_EQUALS    => '\kalanis\kw_forms\Rules\OutRangeEquals',
        IRules::IS_FILLED              => '\kalanis\kw_forms\Rules\IsFilled',
        IRules::IS_NOT_EMPTY           => '\kalanis\kw_forms\Rules\IsFilled',
        IRules::IS_EMPTY               => '\kalanis\kw_forms\Rules\IsEmpty',
        IRules::SATISFIES_CALLBACK     => '\kalanis\kw_forms\Rules\ProcessCallback',
        IRules::IS_EMAIL               => '\kalanis\kw_forms\Rules\IsEmail',
        IRules::IS_DOMAIN              => '\kalanis\kw_forms\Rules\IsDomain',
        IRules::IS_ACTIVE_DOMAIN       => '\kalanis\kw_forms\Rules\IsActiveDomain',
        IRules::URL_EXISTS             => '\kalanis\kw_forms\Rules\UrlExists',
        IRules::IS_JSON_STRING         => '\kalanis\kw_forms\Rules\IsJsonString',
//        IRules::IS_POST_CODE           => '\kalanis\kw_forms\Rules\External\IsPostCode',  // too many formats for simple check, use regex
//        IRules::IS_TELEPHONE           => '\kalanis\kw_forms\Rules\External\IsPhone',  // too many formats for simple check, use regex
//        IRules::IS_EU_VAT              => '\kalanis\kw_forms\Rules\External\IsEuVat',  // too many formats, needs some library for checking
//        IRules::IS_DATE                => '\kalanis\kw_forms\Rules\External\IsDate',  // too many formats, needs some library for checking
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
