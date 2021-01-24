<?php

namespace kalanis\kw_forms\Controls;


/**
 * Class Radio
 * @package kalanis\kw_forms\Controls
 * Render input for selecting by radio checkbox
 */
class Radio extends AControl
{
    use TChecked;

    public $templateInput = '<input type="radio" value="%1$s"%2$s />';

    public function set(string $alias, $value = null, string $label = '', $checked = '')
    {
        $this->setEntry($alias, $value, $label);
        $this->setChecked($checked);
        $this->setAttribute('id', $this->getKey());
        return $this;
    }

    protected function fillTemplate(): string
    {
        return '%2$s %1$s';
    }

    public function getOriginalValue()
    {
        return $this->originalValue;
    }

    public function renderInput($attributes = null): string
    {
        $this->fillParent();
        $this->addAttributes($attributes);
        if (!($this->parent instanceof RadioSet)) {
            $this->setAttribute('name', $this->getKey());
        }
        return $this->wrapIt(sprintf($this->templateInput, strval($this->getOriginalValue()), $this->renderAttributes(), $this->renderChildren()), $this->wrappersInput);
    }

    public function renderLabel($attributes = []): string
    {
        $this->fillParent();
        return parent::renderLabel($attributes);
    }

    protected function fillParent(): void
    {
        if ($this->parent instanceof RadioSet) {
            $this->setAttribute('name', $this->parent->getAttribute('name'));
            $this->setAttribute('id', $this->parent->getKey() . '_' . strval($this->getOriginalValue()));
        }
    }
}