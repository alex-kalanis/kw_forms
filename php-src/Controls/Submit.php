<?php

namespace kalanis\kw_forms\Controls;


/**
 * Class Submit
 * @package kalanis\kw_forms\Controls
 * Form element for submit button
 */
class Submit extends Button
{
    protected $templateLabel = '%2$s';
    protected $templateInput = '<input type="submit" value="%1$s"%2$s />';
    protected $defaultAlias = 'submit';

    /**
     * Check if form was sent by this button
     * @var boolean
     */
    protected $submitted = false;

    /**
     * Set if form has been sent by this submit button
     * @param boolean $value
     * @return $this
     */
    public function setSubmitted(bool $value): self
    {
        $this->submitted = $value;
        return $this;
    }

    /**
     * Get if form has been sent by this submit button
     * @return boolean
     */
    public function getSubmitted(): bool
    {
        return $this->submitted;
    }
}
