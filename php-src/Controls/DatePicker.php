<?php

namespace kalanis\kw_forms\Controls;


class DatePicker extends AControl
{
    protected $dateFormat = 'Y-m-d';
    protected $dateClass = 'datepicker';

    public function set(string $alias, ?string $originalValue = null, string $label = ''): self
    {
        $this->setEntry($alias, $originalValue, $label);
        $this->setAttribute('class', $this->dateClass);
        return $this;
    }

    public function setDateFormat(string $dateFormat): self
    {
        $this->dateFormat = $dateFormat;
        return $this;
    }

    public function getDateFormat(): string
    {
        return $this->dateFormat;
    }

    public function setValue($value): TValue
    {
        if (is_numeric($value)) {
            $this->value = $value;
        } elseif (!empty($value)) {
            $this->value = strtotime($value);
        }
        return $this;
    }

    public function renderInput($attributes = null): string
    {
        $this->addAttributes($attributes);
        if (!empty($this->value)) {
            $value = date($this->dateFormat, $this->value);
        } else {
            $value = $this->value;
        }
        $this->setAttribute('name', $this->getKey());
        return $this->wrapIt(sprintf($this->templateInput, $value, $this->renderAttributes(), $this->renderChildren()), $this->wrappersInput);
    }
}
