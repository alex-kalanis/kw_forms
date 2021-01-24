<?php

namespace kalanis\kw_forms\Controls\Security\Captcha;


use ArrayAccess;
use kalanis\kw_rules\Interfaces\IRules;


/**
 * Class Text
 * @package kalanis\kw_forms\Controls\Security\Captcha
 * Text-filling captcha
 */
class Text extends AGraphical
{
    public function set(string $alias, ArrayAccess &$session, string $errorMessage, string $font = '/usr/share/fonts/truetype/freefont/FreeMono.ttf'): self
    {
        $this->font = $font;
        $text = strtoupper($this->generateRandomString(8));

        $this->setEntry($alias, null, $text);
        $this->fillSession($alias, $session, $text);
        parent::addRule(IRules::SATISFIES_CALLBACK, $errorMessage, [$this, 'checkFillCaptcha']);
        return $this;
    }

    public function addRule(string $ruleName, string $errorText, ...$args): void
    {
        // no additional rules applicable
    }

    public function checkFillCaptcha($value): bool
    {
        $formName = $this->getKey() . '_last';
        return $this->session->offsetExists($formName) && (strval($this->session->offsetGet($formName)) == strval($value));
    }
}
