<?php

namespace kalanis\kw_forms\Controls\Security\Captcha;


/**
 * Define no captcha to render
 */
class Disabled extends ACaptcha
{
    public function getRules(): array
    {
        return [];
    }

    public function renderInput($attributes = null): string
    {
        return '';
    }

    public function renderLabel($attributes = array()): string
    {
        return  '';
    }

    public function renderErrors($errors): string
    {
        return '';
    }
}
