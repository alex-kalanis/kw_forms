<?php

namespace kalanis\kw_forms\Controls\Security;


use ArrayAccess;
use kalanis\kw_forms\Controls\Factory;
use kalanis\kw_forms\Interfaces;


/**
 * Class Captcha
 * @package kalanis\kw_forms\Controls\Security
 * Select captcha for displaying and processing (mobile, PC)
 * Valid captcha can return true even after a little time (not need to draw it again)
 */
class Captcha
{
    const TYPE_DISABLED = 1;
    const TYPE_TEXT = 2;
    const TYPE_MATH = 3;
    const TYPE_COLOUR = 4;
    const TYPE_NOCAPTCHA = 5;

    /** @var Factory */
    protected $factory;

    /** @var string */
    protected $captchaError;

    /** @var Interfaces\ITimeout */
    protected $libTimeout = null;

    public function __construct(Factory $factory, Interfaces\ITimeout $libTimeout = null)
    {
        $this->factory = $factory;
        $this->libTimeout = $libTimeout;

        $this->captchaError = 'The CAPTCHA wasn\'t entered correctly. Please try it again.'; //TODO lang
    }

    public function getCaptcha(int $type, ArrayAccess &$session, string $alias = 'captcha'): Captcha\ACaptcha
    {
        switch ($type) {
            case static::TYPE_DISABLED:
                return $this->factory->getCaptchaDisabled($alias)->setTimeout($this->libTimeout);
            case static::TYPE_TEXT:
                return $this->factory->getCaptchaText($alias, $session, $this->captchaError)->setTimeout($this->libTimeout);
            case static::TYPE_MATH:
                return $this->factory->getCaptchaMath($alias, $session, $this->captchaError)->setTimeout($this->libTimeout);
            case static::TYPE_COLOUR:
                return $this->factory->getCaptchaColour($alias, $session, $this->captchaError)->setTimeout($this->libTimeout);
            default:
                return $this->factory->getNocaptcha($alias, $this->captchaError)->setTimeout($this->libTimeout);
        }
    }
}
