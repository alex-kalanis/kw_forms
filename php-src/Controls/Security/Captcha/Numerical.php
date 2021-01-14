<?php

namespace kalanis\kw_forms\Controls\Security\Captcha;


use ArrayAccess;
use kalanis\kw_rules\Interfaces\IRules;
use kalanis\kw_rules\TRules;


/**
 * Class Numerical
 * @package kalanis\kw_forms\Controls\Security\Captcha
 * Numerical operation solving captcha
 */
class Numerical extends Text
{
    public function set(string $alias, ArrayAccess &$session, string $errorMessage, string $font = '/usr/share/fonts/truetype/freefont/FreeMono.ttf'): parent
    {
        $this->font = $font;
        $this->session = $session;

        $num1 = mt_rand(0, 9);
        $num2 = mt_rand(0, 9);
        $text = ' ' . $num1 . ' + ' . $num2 . ' =';

        $this->setEntry($alias, null, $text);
        $this->fillSession($alias, $session, strval($num1 + $num2));
        TRules::addRule(IRules::SATISFIES_CALLBACK, $errorMessage, [$this, 'checkFillCaptcha']);
        return $this;
    }

    protected function checkFillCaptcha($value): bool
    {
        $formName = $this->alias . '_last';
        return (intval($this->session->offsetGet($formName)) == intval($value));
    }

    public function renderLabel($attributes = null): string
    {
        return '';
    }

    public function renderInput($attributes = null): string
    {
        return parent::renderLabel() . ' '. parent::renderInput($attributes);
    }
}
