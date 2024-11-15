<?php

namespace kalanis\kw_forms\Controls;


use kalanis\kw_forms\Interfaces\IMultiValue;


/**
 * Class SelectList
 * @package kalanis\kw_forms\Controls
 * Form element for selection - long list with multiple values to set
 */
class SelectList extends AControl implements IMultiValue
{
    use TMultiple;

    protected string $templateInput = '<select %2$s>%3$s</select>';

    /**
     * Create form element Select - variant for list
     * @param string $alias
     * @param string $label
     * @param iterable<string, string|SelectOption> $children
     * @param int|null $size
     * @return $this
     */
    public function set(string $alias, string $label = '', iterable $children = [], ?int $size = null): self
    {
        foreach ($children as $childAlias => $child) {
            if ($child instanceof SelectOption) {
                $this->addChild($child, $childAlias);
            } else {
                $this->addOption(strval($childAlias), $childAlias, strval($child));
            }
        }
        $this->setEntry($alias, '', $label);
        if (!is_null($size) && (0 != $size)) {
            $this->setSize($size);
        }
        $this->setAttribute('id', $this->getKey());
        return $this;
    }

    /**
     * Add simple option into select
     * @param string $alias
     * @param string|int|float|bool|null $value
     * @param string $label
     * @return SelectOption
     */
    public function addOption(string $alias, $value, string $label = ''): SelectOption
    {
        $option = new SelectOption();
        $option->setEntry($alias, $value, $label);
        $this->addChild($option, $alias);
        return $option;
    }

    public function getValue()
    {
        foreach ($this->children as $child) {
            if ($child instanceof SelectOption) {
                $value = $child->getValue();
                if (!empty($value)) {
                    return $value;
                }
            }
        }
        return '';
    }

    public function setValue($value): void
    {
        foreach ($this->children as $child) {
            if ($child instanceof SelectOption) {
                $child->setValue($value);
            }
        }
    }

    public function getValues(): array
    {
        $result = [];
        foreach ($this->children as $child) {
            if ($child instanceof SelectOption) {
                $result[$child->getKey()] = $child->getValue();
            }
        }
        return $result;
    }

    public function setValues(array $data): void
    {
        foreach ($this->children as $child) {
            if ($child instanceof SelectOption) {
                $child->setValue('');
            }
        }
        foreach ($data as $value) {
            foreach ($this->children as $child) {
                if ($child instanceof SelectOption) {
                    if ($child->getOriginalValue() == $value) {
                        $child->setValue($child->getOriginalValue());
                        break;
                    }
                }
            }
        }
    }

    public function renderInput($attributes = null): string
    {
        $this->addAttributes($attributes);
        $this->setAttribute('name', $this->getMultiple() ? $this->getKey() . '[]' : $this->getKey());
        return $this->wrapIt(sprintf($this->templateInput, '', $this->renderAttributes(), $this->renderChildren()), $this->wrappersInput);
    }
}
