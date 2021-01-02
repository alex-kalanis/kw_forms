<?php

namespace kalanis\kw_forms\Interfaces;


use kalanis\kw_templates\HtmlElement\IHtmlElement;


/**
 * Interface IWrapper
 * @package kalanis\kw_forms\Interfaces
 * What can be accessed by wrappers
 */
interface IWrapper extends ITemplateError
{
    /**
     * Prida wrapper pro cely objekt ( v metode render() )
     * @param string|array $wrapper
     * @param mixed $attributes
     * @return $this
     */
    public function addWrapper($wrapper, $attributes = null);

    /**
     * Prida wrapper pro jednotlive potomky ( v metode renderChild() )
     * @param string|array $wrapper
     * @param mixed $attributes
     * @return $this
     */
    public function addWrapperChild($wrapper, $attributes = null);

    /**
     * Prida wrapper pro labely ( v metode renderLabel() )
     * @param string|array $wrapper
     * @param mixed $attributes
     * @return $this
     */
    public function addWrapperLabel($wrapper, $attributes = null);

    /**
     * Prida wrapper pro inputy ( v metode renderInput() )
     * @param string|array $wrapper
     * @param mixed $attributes
     * @return $this
     */
    public function addWrapperInput($wrapper, $attributes = null);

    /**
     * Prida wrapper pro obsah potomku ( v metode renderChildren() )
     * @param string|array $wrapper
     * @param mixed $attributes
     * @return $this
     */
    public function addWrapperChildren($wrapper, $attributes = null);

    /**
     * Prida wrapper pro obsah chybove zpravy ( v metode renderErrors() )
     * @param IHtmlElement|array|string $wrapper
     * @param string|array $attributes
     * @return $this
     */
    public function addWrapperError($wrapper, $attributes = null);

    /**
     * Prida wrapper pro obsah chybove zpravy ( v metode renderErrors() )
     * @param IHtmlElement|array|string $wrapper
     * @param string|array $attributes
     * @return $this
     */
    public function addWrapperErrors($wrapper, $attributes = null);
}
