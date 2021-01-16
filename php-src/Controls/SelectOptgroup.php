<?php

namespace kalanis\kw_forms\Controls;


use kalanis\kw_forms\Exceptions\RenderException;


/**
 * Class SelectOptgroup
 * @package kalanis\kw_forms\Controls
 * Form element for selection - option group
 */
class SelectOptgroup extends AControl
{
    protected $templateLabel = '<optgroup label="%2$s">';
    protected $templateInput = '%3$s</optgroup>';

    /**
     * Vytvori element formularoveho prvku Optgroup
     * @param string $alias
     * @param mixed $value
     * @param mixed $label
     * @param array $children
     */
    public function set(string $alias, $value = null, string $label = '', array $children = [])
    {
        $this->setEntry($alias, $value, $label);
    }

    public function getValues()
    {
        return $this->values;
    }

    /**
     * Clear wrappers, there should be none around <optgroup>
     * @param string[]|string $attributes
     * @return string
     * @throws RenderException
     */
    public function renderLabel($attributes = null): string
    {
        $this->resetWrappers();
        return parent::renderLabel($attributes);
    }

    /**
     * Clear wrappers, there should be none around <optgroup>
     * @param string[]|string $attributes
     * @return string
     * @throws RenderException
     */
    public function renderInput($attributes = null): string
    {
        $this->resetWrappers();
        return parent::renderInput($attributes);
    }
}