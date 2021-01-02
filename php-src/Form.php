<?php

namespace kalanis\kw_forms;


use ArrayAccess;
use kalanis\kw_forms\Cache\Storage;
use kalanis\kw_forms\Controls\TWrappers;
use kalanis\kw_storage\Interfaces\IStorage;
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
    use THtmlElement;
    use TWrappers;

    /** @var Controls\Factory */
    protected $controlFactory = null;
    /** @var Storage */
    protected $storage = null;
    /** @var ArrayAccess */
    protected $entries = null;
    /** @var ArrayAccess|null */
    protected $files = null;

    protected $isValid = true;

    /**
     * @var AHtmlElement
     */
    protected $parent;
    protected $value;
    protected $values;
    protected $label;
    protected $labels;

    /** @var Controls\AControl[] */
    protected $controls = [];

    /**
     * 1 id(for=""), 2 labelText,  3 attributy
     * @var string
     */
    protected $templateLabel = '<label for="%1$s"%3$s>%2$s</label>';

    public function __construct(ArrayAccess $entries, ?ArrayAccess $files = null, ?IStorage $storage = null, ?string $alias = '', ?IHtmlElement $parent = null)
    {
        $alias = "$alias";
        $this->alias = $alias;
        $this->setAttribute('name', $alias);

        $this->controlFactory = new Controls\Factory();
        $this->storage = new Storage($storage);
        $this->entries = $entries;
        $this->files = $files;
        $this->alias = $alias;
        $this->storage->setAlias($alias);

        // defaultni method
        $this->setMethod('post');

        /** Nastavi vychozi layout tabulky */
        $this->setLayout();

        $this->setParent($parent);

        $this->setupCsrf();
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
        $this->setValue($child->getAttribute('value'));
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
            if ($child instanceof Controls\Checkboxes) { // checkboxy maji sva specifika
                if (isset($data[$alias])) {
                    $child->setValue($data[$alias]);
                } // pokud se jedna o data z objektu a neni pro dany checkboxes hodnota nastavena, nememi se
            } elseif ($child instanceof Controls\Checkbox) { // checkboxy maji sva specifika
                if (isset($data[$alias])) {
                    $child->checked(true);
                } else {
                    $child->checked(false);
                }
            } elseif ($child instanceof Controls\Submit) { // submity maji sva specifika
                if (isset($data[$alias])) {
                    $child->setSubmitted(true);
                }
            } else {
                $child->setValue(isset($data[$alias]) ? $data[$alias] : null);
            }
        }
        return $this;
    }

    /**
     * Nastavi value objektu, nebo potomku
     * nebo nastavi value childu
     * @param mixed $value
     * @param string $alias
     * @return $this
     */
    public function setValue($value = null, $alias = null)
    {
        if ($alias === null) {
            if (empty ($this->controls)) {
                $this->value = $value;
            } else {
                $this->setValues((array)$value);
            }
        } else {
            $this->values[$alias] = $value;
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
    public function getValue($alias = null)
    {
        if ($alias === null) {
            if (empty ($this->controls)) {
                if (($this instanceof Controls\Checkbox) || ($this instanceof Controls\Radio)) {
                    if ($this->checked()) {
                        return $this->value;
                    } else {
                        return null;
                    }
                }
                return $this->value;
            } else {
                return $this->getValues();
            }
        } else {
            if (isset($this->controls[$alias])) {
                return $this->controls[$alias]->getValue();
            } else {
                return null;
            }
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
    public function getLabel($alias = null)
    {
        if ($alias === null) {
            return $this->label;
        } else {
            if (isset($this->controls[$alias])) {
                return $this->controls[$alias]->getLabel();
            }
            return null;
        }
    }

    /**
     * Nastavi label objektu
     * nebo nastavi label childu
     * @param string $value
     * @param string $alias
     * @return $this
     */
    public function setLabel($value = null, $alias = null)
    {
        if ($alias === null) {
            $this->label = $value;
        } else {
            $this->labels[$alias] = $value;
            if (isset($this->controls[$alias])) {
                $this->controls[$alias]->setLabel($value);
            }
        }
        return $this;
    }

    public function setTemplate($string)
    {
        $this->template = $string;
    }

    /**
     * Vyrenderuje label formularoveho prvku
     * @param (string|array) $attributes
     * @return string
     */
    public function renderLabel($attributes = array())
    {
        if ($this->label) {
            return $this->wrapIt(sprintf($this->templateLabel, $this->getAttribute('id'), $this->getLabel(), $this->renderAttributes($attributes)), $this->wrappersLabel);
        }
        return '';
    }

    public function addCsrf(ArrayAccess $cookie): self
    {
        /** Check if form has been sended */
        $this->addControl($this->controlFactory->getCsrf($this->alias, $cookie));
        return $this;
    }

    /**
     * Nastavi metodu formulare
     * @param string $param
     * @return void
     */
    public function setMethod($param = null)
    {
        $this->setAttribute('method', $param);
    }

    /**
     * Ziska metodu formulare
     * @return string
     */
    public function getMethod()
    {
        return $this->getAttribute('method');
    }

    /**
     * Vyrenderuje Form
     * @param string|array $attributes
     * @return string
     */
    public function render($attributes = []): string
    {
        $this->beforeRender();

        $this->addAttributes($attributes);

        return sprintf($this->template, $this->renderAttributes(), $this->renderErrors(), $this->renderChildren());
    }

    /**
     * Renderuje start tag formulare a hidden elementy
     * @param string|array $attributes
     * @param bool $noControls
     * @return string
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
     * Alias renderStart()
     * @param string|array $attributes
     * @return string
     */
    public function start($attributes = [])
    {
        return $this->renderStart($attributes);
    }

    /**
     * Renderuje end tag formulare
     * @return string
     */
    public function renderEnd()
    {
        return $this->templateEnd;
    }

    /**
     * Alias renderEnd()
     * @return string
     */
    public function end()
    {
        return $this->renderEnd();
    }

    public function useAutoIds($value = null)
    {
        if ($value === null) {
            return $this->useAutoIds;
        } else if (is_bool($value)) {
            return $this->useAutoIds = $value;
        }
    }

    /**
     * Renderuje errory vsech elementu Formu
     * @return string
     */
    public function renderErrors()
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
     * Renderuje errory vsech elementu Formu a vrati jako pole indexovane aliasy inputu
     * @return string[]
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
     * Renderuje vsechny prvky formu
     * doplni chybjejici labels
     * @return string
     */
    public function renderChildren()
    {
        $return = '';
        $hidden = '';
        foreach ($this->controls as $alias => $child) {

            if ($child instanceof AHtmlElement) {
                if ($child instanceof AForm) {
                    if ($child->rendered()) {
                        continue;
                    }
                    if (($child->getLabel() === null) && isset($this->labels[$alias])) {
                        $child->setLabel($this->labels[$alias]);
                    }
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

    public function setSentValues()
    {
        $this->setValues(((array)$this->entries + (array)$this->files));
    }

    public function isSubmitted()
    {
        if (isset($this->entries[$this->csrfTokenAlias])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Zkontroluje zda je splneno odeslani formulare a zahaji kontrolu prvku formulare
     * @return boolean
     */
    public function process()
    {
        if ($this->isSubmitted()) {
            $this->setSentValues();
            if ($this->validate() === true) {
                return true;
            } else {
                $this->resetProtectionToken();
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Check if data is set inside storage
     * @return bool
     */
    public function isStored(): bool
    {
        return $this->storage->isStored();
    }

    /**
     * Save current form data in storage
     */
    public function store(): void
    {
        $this->storage->store($this->getValues(), \Config::CACHE_DAY);
    }

    /**
     * Load data from storage into form
     */
    public function loadStored(): void
    {
        $this->setValues($this->storage->load());
    }

    /**
     * smaze data formulare v session
     */
    public function deleteStored(): void
    {
        $this->storage->delete();
    }

    /**
     * Nastavi globalni chybu na formulari a pripadne na jeho prvku,<br />
     * pokud jsou vyplneny parametry
     * @param null|string $alias
     * @param string $errorText
     */
    public final function triggerError($alias = null, $errorText = '')
    {
        $this->isValid = false;

        if (isset($this->controls[$alias]) && !empty($errorText)) {
            $this->controls[$alias]->triggerError($errorText);
        }
    }

    /**
     * Vyresetuje priznak valid formulare<br />
     * a umozni tak zahajit validaci "na zelene louce"
     */
    public final function resetValid(): self
    {
        $this->isValid = true;
        return $this;
    }

    /**
     * Zjisti zda je formular validni
     * @return boolean
     */
    public final function isValid(): bool
    {
        return ($this->validate() && $this->isValid);
    }

    /**
     * Validace formulare,<br />
     * probiha tak ze validuje vsechny childy a sam sebe
     * @return boolean
     */
    public function validate(): bool
    {
        $validation = true;
        foreach ($this->controls as $child) {
            if (($child instanceof Controls\AControl) && !$child->validate($child)) {
                $validation = false;
            }
        }

        return ($validation && $this->isValid);
    }

    /**
     * Nastavi layout formulare
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