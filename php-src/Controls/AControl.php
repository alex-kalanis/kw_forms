<?php

namespace kalanis\kw_forms\Controls;


use kalanis\kw_forms\Exceptions\RenderException;
use kalanis\kw_rules\Interfaces;
use kalanis\kw_rules\Rules;
use kalanis\kw_rules\TValidate;
use kalanis\kw_templates\HtmlElement\IHtmlElement;
use kalanis\kw_templates\HtmlElement\THtmlElement;


/**
 * Class AControl
 * @package kalanis\kw_forms\Controls
 * Abstraction of control entry - which will be rendered
 */
abstract class AControl implements Interfaces\IValidate, IHtmlElement
{
    use THtmlElement;
    use TKey;
    use TLabel;
    use TValidate;
    use TValue;

    /** @var string|null */
    protected $originalValue = null;

    // 1 label, 2 input, 3 errors
    protected $template = '%1$s %2$s %3$s';
    // 1 value, 2 attributes, 3 children
    protected $templateInput = '';

    protected $inputRendered = false;
    protected $errorsRendered = false;

    protected function whichFactory(): Interfaces\IRuleFactory
    {
        return new Rules\Factory();
    }

    /**
     * @param string $key
     * @param string|object|null $originalValue
     * @param string $label
     * @return $this
     */
    public function setEntry(string $key, $originalValue = null, string $label = ''): self
    {
        $this->setKey($key);
        $this->originalValue = $originalValue;
        $this->setLabel($label);
        return $this;
    }

    /**
     * Return input entry in HTML
     * @param string|string[]|array|null $attributes
     * @return string
     * @throws RenderException
     */
    public function renderInput($attributes = null): string
    {
        $this->addAttributes($attributes);
        if (!empty($this->value) && ($this->value != $this->originalValue)) {
            $value = $this->value;
        } else {
            $value = $this->originalValue;
        }
        $this->setAttribute('name', $this->getKey());
        return $this->wrapIt(sprintf($this->templateInput, $value, $this->renderAttributes(), $this->renderChildren()), $this->wrappersInput);
    }

    /**
     * Return errors over entry which happened
     * @return string
     * @throws RenderException
     */
    public function renderErrors(): string
    {
        $return = '';
        foreach ($this->errors as $error) {
            $return .= $this->wrapIt(sprintf($this->templateError, $error->getMessage()), $this->wrappersError);
        }
        return empty($return) ? '' : $this->wrapIt($return, $this->wrappersErrors);
    }
}