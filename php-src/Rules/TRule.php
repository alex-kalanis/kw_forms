<?php

namespace kalanis\kw_forms\Rules;


use kalanis\kw_forms\Exceptions\RuleException;


/**
 * Trait TRule
 * @package kalanis\kw_forms\Rules
 * Abstract for checking input - What is available for both usual inputs and files
 */
trait TRule
{
    protected $againstValue = null;

    protected $errorText = '';

    /**
     * @param mixed $againstValue
     * @return self
     * @throws RuleException
     */
    public function setAgainstValue($againstValue): self
    {
        $this->againstValue = $this->checkValue($againstValue);
        return $this;
    }

    /**
     * @param mixed $againstValue
     * @return mixed
     * @throws RuleException
     * Nothing here, but more in children, especially in their traits
     */
    protected function checkValue($againstValue)
    {
        return $againstValue;
    }

    public function setErrorText(string $errorText): self
    {
        $this->errorText = $errorText;
        return $this;
    }
}