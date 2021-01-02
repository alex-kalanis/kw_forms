<?php

namespace kalanis\kw_forms\Controls\Security\Captcha;


use kalanis\kw_forms\Controls\TValidate;
use kalanis\kw_forms\Interfaces;


/**
 * Define no captcha to render
 */
class Disabled extends ACaptcha
{
    public function addRule(string $ruleName, string $errorText, ...$args): TValidate
    {
        // no additional rules applicable
        return $this;
    }

    public function removeRules(): TValidate
    {
        // no rules removal applicable
        return $this;
    }

    public function renderInput($attributes = null): string
    {
        return '';
    }

    public function renderLabel($attributes = array()): string
    {
        return  '';
    }

    public function renderErrors(): string
    {
        return '';
    }

    public function validate(Interfaces\IValidate $entry): bool
    {
        return true;
    }
}
