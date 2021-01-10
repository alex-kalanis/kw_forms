<?php

namespace kalanis\kw_forms\Form;


use kalanis\kw_forms\Interfaces\IInputs;
use kalanis\kw_templates\HtmlElement\TAttributes;


/**
 * Trait TMethod
 * @package kalanis\kw_forms\Form
 * Trait to processing methods of form
 */
trait TMethod
{
    use TAttributes;

    /**
     * Set transfer method of form
     * @param string $param
     * @return void
     */
    public function setMethod(string $param)
    {
        if (in_array($param, [IInputs::INPUT_GET, IInputs::INPUT_POST])) {
            $this->setAttribute('method', $param);
        }
    }

    /**
     * Get that method
     * @return string
     */
    public function getMethod()
    {
        return $this->getAttribute('method');
    }
}
