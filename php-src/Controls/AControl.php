<?php

namespace kalanis\kw_forms\Controls;


use kalanis\kw_forms\Exceptions\RenderException;
use kalanis\kw_forms\Interfaces\IWrapper;
use kalanis\kw_rules\Exceptions\RuleException;
use kalanis\kw_rules\Interfaces;
use kalanis\kw_rules\Rules;
use kalanis\kw_rules\TRules;
use kalanis\kw_templates\HtmlElement\IHtmlElement;
use kalanis\kw_templates\HtmlElement\THtmlElement;


/**
 * Class AControl
 * @package kalanis\kw_forms\Controls
 * Abstraction of control entry - which will be rendered
 * Implementing IValidate because kw_rules are really independent
 */
abstract class AControl implements Interfaces\IValidate, IHtmlElement, IWrapper
{
    use THtmlElement;
    use TKey;
    use TLabel;
    use TValue;
    use TRules;
    use TWrappers;

    /** @var string|null */
    protected $originalValue = null;

    // 1 value, 2 attributes, 3 children
    protected $templateInput = '';

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
        $this->template = $this->fillTemplate();
        return $this;
    }

    /**
     * Because filling main template throws an error
     * 1 label, 2 input, 3 errors
     * @return string
     */
    protected function fillTemplate(): string
    {
        return '%1$s %2$s %3$s';
    }

    /**
     * @return string
     * @throws RenderException
     */
    public function render(): string
    {
        return sprintf($this->template, $this->renderLabel(), $this->renderInput(), $this->renderErrors([]));
    }

    /**
     * Render label on form control
     * @param string|string[] $attributes
     * @return string
     * @throws RenderException
     */
    public function renderLabel($attributes = []): string
    {
        if ($this->label) {
            return $this->wrapIt(sprintf($this->templateLabel, $this->getAttribute('id'), $this->getLabel(), $this->renderAttributes($attributes)), $this->wrappersLabel);
        }
        return '';
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
        return $this->wrapIt(sprintf($this->templateInput, strval($value), $this->renderAttributes(), $this->renderChildren()), $this->wrappersInput);
    }

    /**
     * Return errors over entry which happened
     * @param RuleException[] $errors
     * @return string
     * @throws RenderException
     */
    public function renderErrors($errors): string
    {
        $return = '';
        foreach ($errors as $error) {
            $return .= $this->wrapIt(sprintf($this->templateError, $error->getMessage()), $this->wrappersError);
        }
        return empty($return) ? '' : $this->wrapIt($return, $this->wrappersErrors);
    }

    public function inherit(IHtmlElement $child): IHtmlElement
    {
        $child->addAttributes($this->getAttributes());
        $child->setChildren($this->getChildren());
        if ($child instanceof IWrapper) {
            if (!empty($this->wrappersChild)) {
                $child->addWrapper($this->wrappersChild);
            }

            if (!empty($this->wrappersErrors)) {
                $child->addWrapperErrors($this->wrappersErrors);
            }

            if (!empty($this->wrappersError)) {
                $child->addWrapperError($this->wrappersError);
            }

            if (!empty($this->wrappersInput)) {
                $child->addWrapperInput($this->wrappersInput);
            }

            if (!empty($this->wrappersLabel)) {
                $child->addWrapperLabel($this->wrappersLabel);
            }

            if (!empty($this->templateError)) {
                $child->setTemplateError($this->templateError);
            }
        }
        return $child;
    }
}
