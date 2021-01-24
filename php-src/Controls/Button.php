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

    public function set(string $alias, ?string $label = null, ?string $originalValue = null): self
    {
        if (is_null($label)) {
            $label = $alias;
            $alias = $this->defaultAlias;
        } elseif ('' == $label) {
            $label = $alias;
        }
        if (empty($originalValue)) {
            $originalValue = $label;
        }
        $this->setEntry($alias, $originalValue, $label);
        $this->setAttribute('id', $this->getKey());
        return $this;
    }
}
