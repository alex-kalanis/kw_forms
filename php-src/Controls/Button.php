<?php

namespace kalanis\kw_forms\Controls;


/**
 * Class Button
 * @package kalanis\kw_forms\Controls
 * Form element for button
 */
class Button extends AControl
{
    protected $templateLabel = '';
    protected $templateInput = '<input type="button" value="%1$s"%2$s />';
    protected $defaultAlias = 'button';

    public function set(string $alias, ?string $label = null): self
    {
        if (is_null($label)) {
            $label = $alias;
            $alias = $this->defaultAlias;
        } elseif (empty($label)) {
            $label = $alias;
        }
        $this->setEntry($alias, null, $label);
        return $this;
    }
}
