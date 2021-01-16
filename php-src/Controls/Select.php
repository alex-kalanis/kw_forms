<?php

namespace kalanis\kw_forms\Controls;


/**
 * Class Select
 * @package kalanis\kw_forms\Controls
 * Form element for selection
 */
class Select extends AControl
{
    protected $values = array();
    protected $templateInput = '<select %2$s>%3$s</select>';
    protected $templateOption = '<option value="%1$s"%2$s>%3$s</option>';

    /**
     * Vytvori element formularoveho prvku Select
     * @param string $alias
     * @param mixed $value
     * @param mixed $label
     * @param array $children
     */
    public function set(string $alias, $value = null, string $label = '', array $children = array())
    {
        // prevede hodnoty potomku ktere jsou pole na Optgroupy
        foreach ($children as $childAlias => &$child) {
            if (is_array($child)) {
                $child = new SelectOptgroup();
                $child->set($childAlias, '-');
            }
        }
        $this->setEntry($alias, $value, $label);
    }

    /**
     * Prida option do selectu
     * @param string $value
     * @param string $label
     * @param boolean $selected
     */
    public function addOption($value, $label, $selected = false)
    {
        $this->children[$value] = $label;
        if ($selected) {
            $this->values[] = $value;
        }
        return $this;
    }

    /**
     * Prida Optgroup k Selectu
     *
     * @param string $alias
     * @param mixed $value
     * @param string $label
     * @param array $options
     * @return SelectOptgroup
     */
    public function addOptgroup($alias, $label = null, $value = null, $options = array())
    {
        $opt = new SelectOptgroup();
        $opt->set($alias, $value, $label);
        $this->addChild($opt);
        return $opt;
    }

    /**
     * Vraci pole hodnot $selected vsech potomku
     * @return array
     */
    public function getValues()
    {
        if (empty($this->children)) {
            if (!$this->multiple()) {
                return Other::getFirst($this->values);
            }
            return $this->values;
        } else {
            $returnValues = [];
            foreach ($this->children as $child) {
                if ($child instanceof SelectOptgroup) {
                    $returnValues = array_merge($returnValues, $child->getValues());
                }
            }
            if( empty( $returnValues ) ){
                if (!$this->multiple()) {
                    return Other::getFirst($this->values);
                }
                return $this->values;
            } else {
                return $returnValues;
            }
        }
    }

    /**
     * Nastavi hodnoty
     *
     * @param array $values
     * @return $this
     */
    public function setValues($values = array())
    {
        $this->values = array();

        foreach ($this->children as $child) {
            if ($child instanceof SelectOptgroup) {
                $child->setValues($values);
            }
        }

        if (is_array($values)) {
            foreach ($values as $alias) {
                if (!empty($this->children) && isset($this->children[$alias])) {
                    $this->values[] = "$alias";
                } else if (empty ($this->children)) {
                    $this->values[] = "$alias";
                }
            }
        }

        return $this;
    }

    /**
     * Vyrenderuje input selectu
     * @param (string|array) $attributes
     * @return string
     */
    public function renderInput($attributes = null): string
    {
        if ($this->multiple()) {
            $this->setAttribute('name', $this->getAttribute('name') . '[]');
        }
        return parent::renderInput($attributes);
    }

    /**
     * Nastavi nebo vrati stav nastaveni multiple
     * @param string|null $value
     * @return $this|bool
     */
    public function multiple($value = null)
    {
        if ($value === null) {
            $value = $this->getAttribute('multiple');
            if ($value == 'multiple') {
                return true;
            } else {
                return false;
            }
        } else {
            if ($value && ("$value" !== 'none')) {
                $this->setAttribute('multiple', 'multiple');
                if (!$this->getAttribute('size')) {
                    $this->setAttribute('size', count($this->children));
                }
            } else {
                $this->removeAttribute('multiple');
            }
        }
        return $this;
    }

    public function renderChildren(): string
    {
        // je treba vse porovnavat jako stringy jinak se neda poradne udelat in_array ...
        foreach ($this->values as $key => $value) {
            $this->values[$key] = "$value";
        }

        $return = '';
        foreach ($this->children as $value => $label) {
            if ($label instanceof SelectOptgroup) {
                $return .= $label->render() . "\n";
            } else {
                if (in_array("$value", $this->values, true)) {
                    $selected = ' selected="selected"';
                } else {
                    $selected = '';
                }
                $return .= sprintf($this->templateOption, $value, $selected, $label) . "\n";
            }
        }
        return $return;
    }
}