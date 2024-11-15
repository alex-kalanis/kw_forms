<?php

namespace kalanis\kw_forms\Controls;


use kalanis\kw_forms\Interfaces\IOriginalValue;


/**
 * Class Checkbox
 * @package kalanis\kw_forms\Controls
 * Form element for checkboxes
 */
class Checkbox extends AControl implements IOriginalValue
{
    use TChecked;

    private static int $uniqid = 0;
    protected string $templateInput = '<input type="checkbox" value="%1$s"%2$s />';

    /**
     * @param string $alias
     * @param string|int|float|null $value
     * @param string $label
     * @return $this
     */
    public function set(string $alias, $value = null, string $label = ''): self
    {
        $this->setEntry($alias, $value, $label);
        $this->setAttribute('id', sprintf('%s_%s', $this->getKey(), self::$uniqid));
        self::$uniqid++;
        return $this;
    }

    public function getOriginalValue()
    {
        return $this->originalValue;
    }

    protected function fillTemplate(): string
    {
        return '%2$s %1$s';
    }
}
