<?php

namespace kalanis\kw_forms;


use ArrayAccess;
use kalanis\kw_forms\Controls\AControl;
use kalanis\kw_forms\Controls\TWrappers;
use kalanis\kw_forms\Interfaces\IInputs;
use kalanis\kw_rules\Exceptions\RuleException;
use kalanis\kw_rules\Validate;
use kalanis\kw_templates\AHtmlElement;
use kalanis\kw_templates\HtmlElement\IHtmlElement;
use kalanis\kw_templates\HtmlElement\THtmlElement;


/**
 * Class Form
 * @package kalanis\kw_forms
 * Basic class for work with forms
 */
class Form implements IHtmlElement
{
    use Cache\TStorage;
    use Form\TMethod;
    use THtmlElement;
    use TWrappers;

    /** @var Controls\Factory */
    protected $controlFactory = null;
    /** @var Validate */
    protected $validate = null;
    /** @var ArrayAccess|null */
    protected $entries = null;
    /** @var ArrayAccess|null */
    protected $files = null;
    /** @var string Form label */
    protected $label = '';
    /** @var Controls\AControl[] */
    protected $controls = [];
    /** @var RuleException[][] */
    protected $errors = [];

    /**
     * Main Form template
     * @var string
     * params: %1 attributes, %2 errors, %3 controls
     */
    protected $template = '%2$s<form %1$s>%3$s</form>';

    protected $templateError = '';
    /** @var string Template for error output */
    protected $templateErrors = '<div class="errors">%s</div>';

    /**
     * @var string
     * params: %1 labelText, %2 content
     */
    protected $templateLabel = '<fieldset><legend>%1$s</legend>%2$s</fieldset>';

    public function __construct(string $alias = '', ?IHtmlElement $parent = null)
    {
        $alias = "$alias";
        $this->alias = $alias;
        $this->setAttribute('name', $alias);
        $this->setMethod(IInputs::INPUT_POST);

        $this->controlFactory = new Controls\Factory();
        $this->validate = new Validate();
        $this->setParent($parent);
    }

    public function setInputs(string $method = IInputs::INPUT_POST, ?ArrayAccess $entries = null, ?ArrayAccess $files = null): self
    {
        $this->setMethod($method);
        $this->entries = $entries;
        $this->files = $files;
        return $this;
    }

    public function getControlFactory(): Controls\Factory
    {
        return $this->controlFactory;
    }

    public function addControl(Controls\AControl $control): self
    {
        $this->controls[$control->getAlias()] = $control;
        return $this;
    }

    /**
     * Merge children, attr etc.
     * @param IHtmlElement $child
     */
    public function merge(IHtmlElement $child): void
    {
        $this->setLabel($child->getAttribute('label'));
        $this->setChildren($child->getChildren());
        $this->setAttributes($child->getAttributes());
    }

    /**
     * Get values of all children
     * @return string[]
     */
    public function getValues()
    {
        $array = [];
        foreach ($this->controls as $alias => $child) {
            $array[$alias] = $child->getValue();
        }
        return $array;
    }

    /**
     * Set values to all children, !!undefined values will be set too!!
     * <b>Usage</b>
     * <code>
     *  $form->setValues($this->context->post) // set values from Post
     *  $form->setValues($mapperObject) // set values from other source
     * </code>
     * @param string[] $data
     * @return $this
     */
    public function setValues(array $data = [])
    {
        foreach ($this->controls as $alias => $child) {
            $_alias = ($child instanceof AControl) ? $child->getAlias() : $alias ;
            $child->setValue(isset($data[$_alias]) ? $data[$_alias] : null);
        }
        return $this;
    }

    /**
     * Set value of object or child
     * @param string $alias
     * @param mixed $value
     * @return $this
     */
    public function setValue(string $alias, $value = null)
    {
        if (is_null($alias)) {
            if (!empty($this->controls)) {
                $this->setValues((array)$value);
            }
        } else {
            if (isset($this->controls[$alias])) {
                $this->controls[$alias]->setValue($value);
            }
        }
        return $this;
    }

    /**
     * Get value of object or child
     * @param string $alias
     * @return string|string[]
     */
    public function getValue(string $alias)
    {
        if (isset($this->controls[$alias])) {
            $value = $this->controls[$alias]->getValue();
            return empty($value) ? null : $value ;
        } else {
            return null;
        }
    }

    /**
     * Get labels of all children
     * @return array
     */
    public function getLabels()
    {
        $array = [];
        foreach ($this->controls as $child) {
            $array[$child->getAlias()] = $child->getLabel();
        }
        return $array;
    }

    /**
     * Set labels to all children
     * @param string[] $array
     * @return $this
     */
    public function setLabels(array $array = [])
    {
        foreach ($this->controls as $child) {
            if (isset($array[$child->getAlias()])) {
                $child->setLabel($array[$child->getAlias()]);
            }
        }
        return $this;
    }

    /**
     * Get object or child label
     * @param string $alias
     * @return string|null
     */
    public function getLabel(?string $alias = null)
    {
        if (is_null($alias)) {
            return $this->label;
        } elseif (isset($this->controls[$alias])) {
            return $this->controls[$alias]->getLabel();
        }
        return null;
    }

    /**
     * Set object or child label
     * @param string $value
     * @param string $alias
     * @return $this
     */
    public function setLabel(string $value = null, ?string $alias = null)
    {
        if (is_null($alias)) {
            $this->label = $value;
        } elseif (isset($this->controls[$alias])) {
            $this->controls[$alias]->setLabel($value);
        }
        return $this;
    }

    /**
     * Set sent values and process checks on form
     * @return boolean
     */
    public function process(): bool
    {
        $this->setSentValues();
        return $this->isValid();
    }

    /**
     * Set files first, then entries
     * It's necessary due setting checkboxes - files removes that setting, then normal entries set it back
     */
    public function setSentValues(): void
    {
        $this->setValues((array)$this->files);
        $this->setValues((array)$this->entries);
    }

    /**
     * Form validation
     * Check each control if is valid
     * @return boolean
     */
    public function isValid(): bool
    {
        $this->errors = [];
        $validation = true;
        foreach ($this->controls as $child) {
            if ($child instanceof Controls\AControl) {
                $validation &= $this->validate->validate($child);
                $this->errors[$child->getKey()] = $this->validate->getErrors();
            }
        }

        return $validation;
    }

    public function setTemplate($string): void
    {
        $this->template = $string;
    }

    /**
     * Save current form data in storage
     */
    public function store(): void
    {
        $this->storage->store($this->getValues(), 86400); # day
    }

    /**
     * Load data from storage into form
     */
    public function loadStored(): void
    {
        $this->setValues($this->storage->load());
    }

    /**
     * Render whole form
     * @param string|string[] $attributes
     * @return string
     * @throws Exceptions\RenderException
     */
    public function render($attributes = []): string
    {
        $this->addAttributes($attributes);
        return sprintf($this->template, $this->renderAttributes(), $this->renderErrors(), $this->renderChildren());
    }

    /**
     * Render all errors from controls
     * @return string
     * @throws Exceptions\RenderException
     */
    public function renderErrors(): string
    {
        $errors = $this->renderErrorsArray();
        if (!empty ($errors)) {
            $return = $this->wrapIt(implode('', array_keys($errors)), $this->wrappersErrors);

            return sprintf($this->templateErrors, $return);
        } else {
            return '';
        }
    }

    /**
     * Get all errors from controls and return them as indexed array
     * @return string[]
     * @throws Exceptions\RenderException
     */
    public function renderErrorsArray()
    {
        $errors = [];
        foreach ($this->controls as $child) {
            if ($child instanceof Controls\AControl) {
                if (isset($this->errors[$child->getKey()])) {
                    if (!$child->wrappersErrors()) {
                        $child->addWrapperErrors($this->wrappersError);
                    }
                    $errors[$child->getAlias()] = $child->renderErrors($this->errors[$child->getKey()]);
                }
            }
        }

        return $errors;
    }

    /**
     * Render all form controls, add missing wrappers
     * @return string
     * @throws Exceptions\RenderException
     */
    public function renderChildren(): string
    {
        $return = '';
        $hidden = '';
        foreach ($this->controls as $alias => $child) {

            if ($child instanceof AHtmlElement) {
                if ($child instanceof Controls\AControl) {
                    if (!$child->wrappersLabel()) {
                        $child->addWrapperLabel($this->wrappersLabel);
                    }
                    if (!$child->wrappersInput()) {
                        $child->addWrapperInput($this->wrappersInput);
                    }
                    if (!$child->wrappers()) {
                        $child->addWrapper($this->wrappersChild);
                    }
                }
                if ($child instanceof Controls\Hidden) {
                    $hidden .= $child->render() . PHP_EOL;
                } else {
                    $return .= $child->render() . PHP_EOL;
                }
            } else {
                $return .= $child;
            }
        }

        return $hidden . $this->wrapIt($return, $this->wrappersChildren);
    }

    /**
     * Set form layout
     * @param string $layoutName
     * @return $this
     */
    public function setLayout(string $layoutName = '')
    {
        if (($layoutName == 'inlineTable') || ($layoutName == 'tableInline')) {
            $this->resetWrappers();
            $this->addWrapperChildren('tr')
                ->addWrapperChildren('table', 'class="form"')
                ->addWrapperLabel('td')
                ->addWrapperInput('td')
                ->addWrapperErrors('div', 'class="errors"')
                ->addWrapperError('div');
        } elseif ($layoutName == 'table') {
            $this->resetWrappers();
            $this->addWrapperChildren('table', 'class="form"')
                ->addWrapperChild('tr')
                ->addWrapperLabel('td')
                ->addWrapperInput('td')
                ->addWrapperErrors('div', 'class="errors"')
                ->addWrapperError('div');
        }

        return $this;
    }
}