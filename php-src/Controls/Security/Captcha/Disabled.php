<?php

namespace kalanis\kw_forms\Controls\Security\Captcha;


use kalanis\kw_forms\Interfaces;


/**
 * Define no captcha to render
 */
class Disabled extends ACaptcha
{
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
