<?php

namespace kalanis\kw_forms\Controls\Security\Captcha;


use kalanis\kw_forms\Controls\AControl;
use kalanis\kw_forms\Interfaces;


/**
 * Class ACaptcha
 * @package kalanis\kw_forms\Controls\Security\Captcha
 * Class that define any Captcha
 * You can also pass child captcha by preset timer
 */
abstract class ACaptcha extends AControl
{
    /** @var Interfaces\Timeout|null */
    protected $libTimeout = null;

    public function validate(Interfaces\IValidate $entry): bool
    {
        if ($this->canPass()) {
            $this->errors = []; // isValid checks also this variable
            return true;
        }
        $result = parent::validate($entry);
        if (($this->libTimeout instanceof Interfaces\Timeout) && $result) {
            $this->libTimeout->updateExpire();
        }
        return $result;
    }

    public function renderLabel($attributes = []): string
    {
        return ($this->canPass()) ? '' : parent::renderLabel($attributes);
    }

    public function renderInput($attributes = []): string
    {
        return ($this->canPass()) ? '' : parent::renderInput($attributes);
    }

    public function renderErrors(): string
    {
        return ($this->canPass()) ? '' : parent::renderErrors();
    }

    protected function canPass(): bool
    {
        return ($this->libTimeout instanceof Interfaces\Timeout && $this->libTimeout->isRunning());
    }

    public function setTimeout(Interfaces\Timeout $libTimeout = null): self
    {
        $this->libTimeout = $libTimeout;
        return $this;
    }
}
