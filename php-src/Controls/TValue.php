<?php

namespace kalanis\kw_forms\Controls;


trait TValue
{
    /** @var string|int|float|null */
    protected $value = '';

    /**
     * @param string|int|float|null
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @return string|int|float|null
     */
    public function getValue()
    {
        return $this->value;
    }
}
