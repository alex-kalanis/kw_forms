<?php

namespace kalanis\kw_forms\Controls;


use kalanis\kw_forms\Exceptions\RenderException;
use kalanis\kw_templates\HtmlElement\TAttributes;


/**
 * Wrapper trait for form labels
 * @author Petr Plsek
 * @author Adam Dornak
 */
trait TLabel
{
    use TWrappers;
    use TAttributes;

    /** @var string|null */
    protected $label = null;

    /**
     * 1 id(for=""), 2 labelText,  3 attributes
     * @var string
     */
    protected $templateLabel = '<label for="%1$s"%3$s>%2$s</label>';

    /**
     * Set object label
     * @param string $value
     * @return $this
     */
    public function setLabel($value): self
    {
        $this->label = $value;
        return $this;
    }

    /**
     * Returns object label
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * Render label on form control
     * @param string|array $attributes
     * @return string
     * @throws RenderException
     */
    public function renderLabel($attributes = []): string
    {
        if ($this->label) {
            return $this->wrapIt(sprintf($this->templateLabel, $this->getAttribute('id'), $this->getLabel(), $this->renderAttributes($attributes)), $this->wrappersLabel);
        }
        return '';
    }
}
