<?php

namespace kalanis\kw_forms\Controls\Security\Captcha;


use ArrayAccess;
use kalanis\kw_forms\Controls\TValidate;
use kalanis\kw_forms\Exceptions\RenderException;
use kalanis\kw_forms\Interfaces\IRules;


/**
 * Class Text
 * @package kalanis\kw_forms\Controls\Security\Captcha
 * Text-filling captcha
 */
class Text extends ACaptcha
{
    protected $templateLabel = '<img src="data:image/png;base64,%2$s" id="%1$s" alt="You need to solve this." />';
    protected $templateInput = '<input type="text" value=""%2$s />';
    protected $font = '';
    /** @var ArrayAccess */
    protected $session = null;

    public function set(string $alias, ArrayAccess &$session, string $errorMessage, string $font = '/usr/share/fonts/truetype/freefont/FreeMono.ttf'): self
    {
        $this->font = $font;
        $this->session = $session;
        $text = strtoupper($this->generateRandomString(8));

        $this->setEntry($alias, null, $text);
        $this->fillSession($alias, $session, $text);
        parent::addRule(IRules::SATISFIES_CALLBACK, $errorMessage, [$this, 'checkFillCaptcha']);
        return $this;
    }

    protected function checkFillCaptcha($value): bool
    {
        $formName = $this->alias . '_last';
        return (strtolower(strval($this->session->offsetGet($formName))) == strtolower(strval($value)));
    }

    protected function fillSession(string $alias, ArrayAccess &$session, string $text): void
    {
        $stringNow = $alias . '_now';
        $stringLast = $alias . '_last';

        $session->offsetSet($stringLast, ($session->offsetExists($stringNow) ? $session->offsetGet($stringNow) : null));
        $session->offsetSet($stringNow, $text);
    }

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

    /**
     * Render label on form control
     * @param string|array $attributes
     * @return string
     * @throws RenderException
     */
    public function renderLabel($attributes = array()): string
    {
        if ($this->canPass()) {
            return '';
        }
        return $this->wrapIt(sprintf($this->templateLabel, $this->getAttribute('id'), $this->getImage($this->getLabel()), $this->renderAttributes($attributes)), $this->wrappersLabel);
    }

    protected function getImage(string $text): string
    {
        $im = imagecreatetruecolor(160, 25);

        $white = imagecolorallocate($im, 255, 255, 255);
        $grey = imagecolorallocate($im, 169, 169, 169);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 160, 25, $white);

        imagettftext($im, 20, 0, 9, 19, $black, $this->font, $text);
        imagettftext($im, 20, 0, 11, 21, $black, $this->font, $text);
        imagettftext($im, 20, 0, 10, 20, $black, $this->font, $text);

        for ($i = 0; $i < 3; $i++) {
            imageline($im, 0, $i * 10, 400, $i * 10, $grey);
        }

        for ($i = 0; $i < 16; $i++) {
            imageline($im, $i * 10, 0, $i * 10, 30, $grey);
        }

        ob_start();
        imagepng($im);
        $img = ob_get_contents();
        ob_end_clean();

        imagedestroy($im);

        return $img;
    }

    /**
     * Generate and returns random string with combination of numbers and chars with specified length
     * @param int $stringLength
     * @return string
     * @codeCoverageIgnore
     */
    protected function generateRandomString(int $stringLength = 16): string
    {
        $all = ['1','2','3','4','5','6','7','8','9','0','a','b','c','d','e','f','g','h','i',
            'j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','!','$','%'];
        $string = "";
        for ($i = 0; $i < $stringLength; $i++) {
            $rand = mt_rand(0, count($all) - 1);
            $string .= $all[$rand];
        }
//print_r(["CPT_>"=>$string]);
        return $string;
    }
}
