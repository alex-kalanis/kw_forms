<?php

namespace kalanis\kw_forms\Controls\Security\Captcha;


use kalanis\kw_forms\Controls\AControl;
use kalanis\kw_forms\Interfaces;
use kalanis\kw_rules\Interfaces as IRules;
use kalanis\kw_rules\TValidate;


/**
 * Class ACaptcha
 * @package kalanis\kw_forms\Controls\Security\Captcha
 * Class that define any Captcha
 * You can also pass child captcha by preset timer
 */
abstract class ACaptcha extends AControl
{
    /** @var Interfaces\ITimeout|null */
    protected $libTimeout = null;

    public function addRule(string $ruleName, string $errorText, ...$args): TValidate
    {
        // no additional rules applicable
        return $this;
    }

    public function addRules(iterable $rules = []): TValidate
    {
        // no adding external rules applicable
        return $this;
    }

    public function removeRules(): TValidate
    {
        // no rules removal applicable
        return $this;
    }

    public function validate(IRules\IValidate $entry): bool
    {
        if ($this->canPass()) {
            $this->errors = []; // isValid checks also this variable
            return true;
        }
        $result = parent::validate($entry);
        if (($this->libTimeout instanceof Interfaces\ITimeout) && $result) {
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
        return ($this->libTimeout instanceof Interfaces\ITimeout && $this->libTimeout->isRunning());
    }

    public function setTimeout(Interfaces\ITimeout $libTimeout = null): self
    {
        $this->libTimeout = $libTimeout;
        return $this;
    }
}
