<?php

namespace kalanis\kw_forms\Controls\Security;


use ArrayAccess;
use kalanis\kw_forms\Controls\Hidden;
use kalanis\kw_forms\Interfaces;
use kalanis\kw_rules\Interfaces as IRules;
use kalanis\kw_rules\TValidate;


/**
 * Class Csrf
 * @package kalanis\kw_forms\Controls\Security
 * Hidden entry which adds CSRF check
 * Must be child of hidden due necessity of pre-setting position in render
 */
class Csrf extends Hidden
{
    /** @var Interfaces\ICsrf */
    protected $csrf = null;
    /** @var string */
    protected $csrfTokenAlias = '';

    public function __construct()
    {
        $this->csrf = $this->getCsrfLib();
    }

    protected function getCsrfLib(): Interfaces\ICsrf
    {
        return new Csrf\JWT();
    }

    public function setHidden(string $alias, ArrayAccess &$cookie, string $errorMessage): parent
    {
        $this->csrf->init($cookie);
        $this->setEntry($alias);
        $this->csrfTokenAlias = "{$alias}SubmitCheck";
        $this->setValue($this->csrf->getToken($this->csrfTokenAlias));
        parent::addRule(IRules\IRules::SATISFIES_CALLBACK, $errorMessage, [$this, 'checkToken']);
        return $this;
    }

    protected function checkToken($incomingValue): bool
    {
        if ($this->csrf->checkToken(strval($incomingValue), $this->csrfTokenAlias)) {
            // token reload
            $this->csrf->removeToken($this->csrfTokenAlias);
            $this->setValue($this->csrf->getToken($this->csrfTokenAlias));
            return true;
        } else {
            return false;
        }
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
