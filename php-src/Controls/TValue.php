<?php

namespace kalanis\kw_forms\Controls;


trait TValue
{
    /** @var string */
    protected $value = '';

    public function setValue($value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
