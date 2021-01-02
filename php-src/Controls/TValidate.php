<?php

namespace kalanis\kw_forms\Controls;


use kalanis\kw_forms\Interfaces;
use kalanis\kw_forms\Exceptions\RuleException;
use kalanis\kw_forms\Rules;


trait TValidate
{
    /** @var Interfaces\IRuleFactory */
    protected $rulesFactory = null;
    /** @var Rules\ARule[] */
    protected $rules = [];
    /** @var RuleException[] */
    protected $errors = [];

    /**
     * @param string $ruleName
     * @param string $errorText
     * @param mixed ...$args
     * @return $this
     * @throws RuleException
     */
    public function addRule(string $ruleName, string $errorText, ...$args): self
    {
        $this->setFactory();
        $this->rules[] = $this->rulesFactory->getRule($ruleName)->setErrorText($errorText)->setAgainstValue($args);
        return $this;
    }

    public function removeRules(): self
    {
        $this->rules = [];
        $this->errors = [];
        return $this;
    }

    protected function setFactory(): void
    {
        if (empty($this->rulesFactory)) {
            $this->rulesFactory = $this->whichFactory();
        }
    }

    /**
     * Set which factory will be used
     * @return Interfaces\IRuleFactory
     */
    abstract protected function whichFactory(): Interfaces\IRuleFactory;

    public function validate(Interfaces\IValidate $entry): bool
    {
        $this->errors = [];
        foreach ($this->rules as $rule) {
            try {
                $rule->validate($entry);
            } catch (RuleException $ex) {
                $this->errors[] = $ex;
                while ($ex = $ex->getPrev()) {
                    $this->errors[] = $ex;
                }
            }
        }
        return empty($this->errors);
    }

    /**
     * @return RuleException[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
