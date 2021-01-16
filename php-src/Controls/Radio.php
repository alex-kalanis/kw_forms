<?php

namespace kalanis\kw_forms\Controls;


/**
 * Trida definice formularoveho prvku Radio
 */
class Radio extends AControl
{
    public $template = '%2$s %1$s';
    public $templateInput = '<input type="radio" value="%1$s"%2$s />';

    /**
     * Vytvori element formularoveho prvku Radio
     *
     * @param string $alias
     * @param string $value
     * @param string $label
     * @param type   $checked
     */
    public function set(string $alias, $value = null, string $label = '', $checked = null)
    {
        $this->setEntry($alias, $value, $label);
        $this->checked($checked);
    }

    /**
     * @param array $inputAttrs
     * @param array $labelAttrs
     * @return string
     */
    public function render($inputAttrs = array(), $labelAttrs = array()): string
    {
        if ($this->parent instanceof RadioSet) {
            $this->name($this->parent->name());
            $this->id($this->parent->alias() . '_' . $this->value);
        }
        return parent::render($inputAttrs, $labelAttrs);
    }

    /**
     * @param null $attributes
     * @return string
     */
    function renderInput($attributes = null): string
    {
        if ($this->parent instanceof RadioSet) {
            $this->name($this->parent->name());
            $this->id($this->parent->alias() . '_' . $this->value);
        }
        return parent::renderInput($attributes);
    }

    /**
     * @param array $attributes
     * @return string
     */
    public function renderLabel($attributes = array()): string
    {
        if ($this->parent instanceof RadioSet) {
            $this->name($this->parent->name());
            $this->id($this->parent->alias() . '_' . $this->value);
        }
        return parent::renderLabel($attributes);
    }

    /**
     * nastavi nebo zjisti stav checked
     * @param type $value
     * @return $this
     */
    public function checked($value = null)
    {
        if ($value === null) {
            $value = $this->attr('checked');
            if ($value == 'checked') {
                return true;
            } else {
                return false;
            }
        } else {
            if ($value && ("$value" !== 'none')) {
                $this->attr('checked', 'checked');
            } else {
                unset($this->attributes['checked']);
            }
        }
        return $this;
    }
}