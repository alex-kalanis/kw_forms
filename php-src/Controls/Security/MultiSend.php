<?php

namespace kalanis\kw_forms\Controls\Security;


use ArrayAccess;
use kalanis\kw_forms\Controls\Hidden;
use kalanis\kw_rules\Interfaces as IRules;
use kalanis\kw_rules\TValidate;


/**
 * Class MultiSend
 * @package kalanis\kw_forms\Controls\Security
 * Hidden entry which adds Multisend check
 * Must be child of hidden due necessity of pre-setting position in render
 */
class MultiSend extends Hidden
{
    /** @var ArrayAccess */
    protected $cookie = null;

    public function setHidden(string $alias, ArrayAccess &$cookie, string $errorMessage): parent
    {
        $this->cookie = $cookie;
        $this->setEntry($alias);
        $this->setValue(uniqid('multisend', true));
        $this->addCheckToStack($this->getValue());
        parent::addRule(IRules\IRules::SATISFIES_CALLBACK, $errorMessage, [$this, 'checkMulti']);
        return $this;
    }

    protected function checkMulti($incomingValue): bool
    {
        return $this->removeExistingCheckFromStack(strval($incomingValue));
    }

    protected function addCheckToStack(string $value): void
    {
        $hashStack = json_decode($this->cookie->offsetGet($this->alias . 'SubmitCheck'), true);
        $hashStack[$value] = 'FORM_SENDED';
        $this->cookie->offsetSet($this->alias . 'SubmitCheck', json_encode($hashStack));
    }

    protected function removeExistingCheckFromStack(string $value): bool
    {
        $hashStack = json_decode($this->cookie->offsetGet($this->alias . 'SubmitCheck'), true);
        if (isset($hashStack[$value])) {
            unset($hashStack[$value]);
            $this->cookie->offsetSet($this->alias . 'SubmitCheck', json_encode($hashStack));
            return true;
        }
        return false;
    }

    public function addRule(string $ruleName, string $errorText, ...$args): TValidate
    {
        // no additional rules applicable
        return $this;
    }

    public function addRules(iterable $rules = []): TValidate
    {
        // no rules add applicable
        return $this;
    }

    public function removeRules(): TValidate
    {
        // no rules removal applicable
        return $this;
    }

    public function renderErrors(): string
    {
        return '';
    }
}
