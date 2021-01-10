<?php

namespace kalanis\kw_forms\Controls;


/**
 * Class Submit
 * @package kalanis\kw_forms\Controls
 * Form element for submit button
 */
class Submit extends Button
{
    protected $defaultAlias = 'submit';
    protected $templateInput = '<input type="submit" value="%1$s"%2$s />';
    protected $templateLabel = '%2$s';

    /**
     * Check if form was sent by this button
     * @var boolean
     */
    protected $submitted = false;

    public function setValue($value): TValue
    {
        $this->submitted = !is_null($value);
        return $this;
    }

    public function getValue(): string
    {
        return $this->submitted ? $this->originalValue : '' ;
    }
}
