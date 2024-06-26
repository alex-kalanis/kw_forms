<?php

namespace kalanis\kw_forms\Controls;


/**
 * Class Hidden
 * @package kalanis\kw_forms\Controls
 * Form element for hidden entry
 */
class Hidden extends AControl
{
    protected string $templateError = '';
    protected string $templateLabel = '';
    protected string $templateInput = '<input type="hidden" value="%1$s"%2$s />';

    public function set(string $alias, ?string $value = null): self
    {
        $this->setEntry($alias, $value);
        return $this;
    }
}
