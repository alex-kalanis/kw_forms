<?php

namespace kalanis\kw_forms\Controls;


/**
 * Class Checkbox
 * @package kalanis\kw_forms\Controls
 * Form element for checkboxes
 */
class Checkbox extends AControl
{
    private static $uniqid = 0;
    protected $template = '%2$s %1$s';
    protected $templateInput = '<input type="checkbox" value="%1$s"%2$s />';

    public function set(string $alias, $value = null, string $label = '')
    {
        $this->setEntry($alias, $value, $label);
        $this->setAttribute('id', sprintf('%s_%s', $this->alias, self::$uniqid));
        self::$uniqid++;
    }

    public function setValue($value): TValue
    {
        $this->setChecked($value);
        return $this;
    }

    public function getValue(): string
    {
        return $this->getChecked() ? $this->originalValue : '' ;
    }

    protected function setChecked($value)
    {
        if ($value && ("$value" !== 'none')) {
            $this->setAttribute('checked', 'checked');
        } else {
            $this->removeAttribute('checked');
        }
        return $this;
    }

    protected function getChecked()
    {
        return ('checked' == $this->getAttribute('checked'));
    }

    public function render(): string
    {
        $this->beforeRender();
        return parent::render();
    }

    public function renderLabel($attributes = []): string
    {
        $this->beforeRender();
        return parent::renderLabel($attributes);
    }

    public function renderInput($attributes = null): string
    {
        $this->beforeRender();
        return parent::renderInput($attributes);
    }

    protected function beforeRender()
    {
        if ($this->parent instanceof Checkboxes) {
            if (false === strpos($this->getAttribute('name'), $this->parent->getAttribute('name'))) {
                $this->setAttribute('name', $this->parent->getAttribute('name') . '[]');
                $this->setAttribute('id', $this->parent->getAlias() . '_' . $this->alias);
            }
        }
    }
}