<?php

namespace kalanis\kw_forms;


use ArrayAccess;
use kalanis\kw_forms\Controls\AControl;
use kalanis\kw_forms\Controls\TWrappers;
use kalanis\kw_forms\Interfaces\IInputs;
use kalanis\kw_templates\AHtmlElement;
use kalanis\kw_templates\HtmlElement\IHtmlElement;
use kalanis\kw_templates\HtmlElement\THtmlElement;


/**
 * Class Form
 * @package kalanis\kw_forms
 * Basic class for work with forms
 * @see \Form
 * @see \AForm
 */
class Form implements IHtmlElement
{
    use Cache\TStorage;
    use Form\TMethod;
    use THtmlElement;
    use TWrappers;

    /** @var Controls\Factory */
    protected $controlFactory = null;
    /** @var ArrayAccess|null */
    protected $entries = null;
    /** @var ArrayAccess|null */
    protected $files = null;
    /** @var string Form label */
    protected $label = '';
    /** @var Controls\AControl[] */
    protected $controls = [];

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
     * Template of start tag
     * @var string
     * params: %1 attributes
     */
    protected $templateStart = '<form %1$s>';

    /** @var string End tag template */
    protected $templateEnd = '</form>';

    /**
     * @var string
     * params: %1 id(for=""), %2 labelText,  %3 attributes
     */
    protected $templateLabel = '<label for="%1$s"%3$s>%2$s</label>';

    public function __construct(string $alias = '', ?IHtmlElement $parent = null)
    {
        $alias = "$alias";
        $this->alias = $alias;
        $this->setAttribute('name', $alias);
        $this->setMethod(IInputs::INPUT_POST);

        $this->controlFactory = new Controls\Factory();
        $this->setParent($parent);
    }

    public function setInputs(string $method = IInputs::INPUT_POST, ?ArrayAccess $entries = null, ?ArrayAccess $files = null): self
    {
        $this->setMethod($method);
        $this->entries = $entries;
        $this->files = $files;
        return $this;
    }

    public function addControl(Controls\AControl $control): self
    {
        $this->controls[$control->getAlias()] = $control;
        return $this;
    }

    /**
     * Merge potomku, attr atd.
     * @param IHtmlElement $child
     */
    public function merge(IHtmlElement $child): void
    {
        $this->setLabel($child->getAttribute('label'));
        $this->setChildren($child->getChildren());
        $this->setAttributes($child->getAttributes());
    }

    /**
     * Vraci pole hodnot $value vsech potomku
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
     * Nastavi potomkum objektu $value, !!nedefinovane hodnoty checkboxu nebudou vynechany!!
     * <b>Pouziti</b>
     * <code>
     *  $form->setValues($this->context->post) // nastavi hodnoty z postu
     *  $form->setValues($formObject) // nastavi hodnoty z jineho formu
     * </code>
     * @param array|Form $data
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
     * Nastavi value objektu, nebo potomku
     * nebo nastavi value childu
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
     * Vrati value objektu, nebo potomku
     * nebo vrati value childu
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
     * Vrati pole labelu $label vsech potomku
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
     * Nastavi potomkum objektu nove labely
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
     * Vrati label objektu
     * nebo vrati label childu
     * @param string $alias
     * @return string
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
     * Nastavi label objektu
     * nebo nastavi label childu
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
        $validation = true;
        foreach ($this->controls as $child) {
            if (($child instanceof Controls\AControl) && !$child->validate($child)) {
                $validation = false;
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
     * Renderuje start tag formulare a hidden elementy
     * @param string|array $attributes
     * @param bool $noControls
     * @return string
     * @throws Exceptions\RenderException
     */
    public function renderStart($attributes = [], bool $noControls = false)
    {
        $this->addAttributes($attributes);
        $return = sprintf($this->templateStart, $this->renderAttributes());
        if (false == $noControls) {
            foreach ($this->controls as $child) {
                if ($child instanceof Controls\Hidden) {
                    $return .= $child->renderInput() . PHP_EOL;
                }
            }
        }

        return $return;
    }

    /**
     * Renderuje end tag formulare
     * @return string
     */
    public function renderEnd(): string
    {
        return $this->templateEnd;
    }

    /**
     * Render all errors from controls
     * @return string
     * @throws Exceptions\RenderException
     */
    public function renderErrors(): string
    {
        $errors = [];
        foreach ($this->controls as $child) {
            if ($child instanceof Controls\AControl) {
                if ($child->getErrors()) {
                    if (!$child->wrappersErrors()) {
                        $child->addWrapperErrors($this->wrappersError);
                    }
                    $errors[$child->getAlias()] = $child->renderErrors();
                }
            }
        }
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
                if ($child->getErrors()) {
                    if (!$child->wrappersErrors()) {
                        $child->addWrapperErrors($this->wrappersError);
                    }
                    $errors[$child->getAlias()] = $child->renderErrors();
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
     * @throws Exceptions\FormsException
     */
    public function setLayout($layoutName = '')
    {
        if (!is_string($layoutName)) {
            throw new Exceptions\FormsException('Metoda setLayout vyzaduje jeden parametr typu string.');
        }

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