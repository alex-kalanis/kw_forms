<?php

namespace kalanis\kw_forms\Controls\Security\Captcha;


use kalanis\kw_forms\Controls\AControl;
use kalanis\kw_forms\Interfaces;
use kalanis\kw_rules\TRules;


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

    public function addRule(string $ruleName, string $errorText, ...$args): TRules
    {
        // no additional rules applicable
        return $this;
    }

    public function addRules(iterable $rules = []): TRules
    {
        // no adding external rules applicable
        return $this;
    }

    public function getRules(): array
    {
        $ruleset = $this->canPass() ? [] : $this->rules;
        if (($this->libTimeout instanceof Interfaces\ITimeout) && !empty($ruleset)) {
            $this->libTimeout->updateExpire();
        }
        return $ruleset;
    }

    public function removeRules(): TRules
    {
        // no rules removal applicable
        return $this;
    }

    public function renderLabel($attributes = []): string
    {
        return $this->canPass() ? '' : parent::renderLabel($attributes);
    }

    public function renderInput($attributes = []): string
    {
        return $this->canPass() ? '' : parent::renderInput($attributes);
    }

    public function renderErrors($errors): string
    {
        return $this->canPass() ? '' : parent::renderErrors($errors);
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
