<?php

namespace kalanis\kw_forms\Controls;


class DateRange extends AControl
{
    public $templateLabel = '<label>%2$s</label>';
    public $templateInput = '%3$s';

    public function set(string $alias, ?string $value = null, string $label = ''): self
    {
        $this->setEntry($alias, $value, $label);
        $this->setChildren([
            (new DatePicker())->set($alias.'[]'),
            (new DatePicker())->set($alias.'[]'),
        ]);
        return $this;
    }
}