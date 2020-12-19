<?php

namespace kalanis\kw_forms;


use kalanis\kw_forms\Adapters\VarsAdapter;
use kalanis\kw_forms\Adapters\FilesAdapter;
use kalanis\kw_forms\Cache\Storage;
use kalanis\kw_storage\Interfaces\IStorage;
use kalanis\kw_templates\AHtmlElement;
use kalanis\kw_templates\HtmlElement\IHtmlElement;
use kalanis\kw_templates\HtmlElement\THtmlElement;

/**
 * Class Form
 * @package kalanis\kw_forms
 * Basic class for work with forms
 * @see \Form
 */
class Form extends AForm
{
    use THtmlElement;

    /** @var Controls\Factory */
    protected $controlFactory = null;
    /** @var Storage */
    protected $storage = null;
    /** @var VarsAdapter */
    protected $entries = null;
    /** @var FilesAdapter */
    protected $files = null;

    protected $isValid = true;

    public function __construct(VarsAdapter $entries, ?FilesAdapter $files = null, ?IStorage $storage = null, ?string $alias = '', ?IHtmlElement $parent = null)
    {
        parent::__construct($alias);
        $this->controlFactory = new Controls\Factory();
        $this->storage = new Storage($storage);
        $this->entries = $entries;
        $this->files = $files;
        $this->storage->setAlias($alias);

        // defaultni method
        $this->setMethod('post');

        /** Nastavi vychozi layout tabulky */
        $this->setLayout();

        $this->setParent($parent);

        $this->_setupCsrf();

    }

    protected function _setupCsrf()
    {
        /** Slouzi pro kontrolu zda byl formular odeslan */
        $this->csrfTokenAlias = "{$this->_alias}SubmitCheck";
        $this->controlFactory->addHidden($this->csrfTokenAlias);

        $this->csrf = $this->_getCsrfLib();

        $this->getCsrfControl()
            ->value($this->csrf->getToken($this->csrfTokenAlias));
        $this->getCsrfControl()
            ->addRule(self::SATISFIES_CALLBACK, $this->_formProtectionErrorString, [$this->csrf, 'checkToken']);
    }

    protected function _getCsrfLib(): \Form\Protection\ICsrf
    {
        return new \Form\Protection\JWT(\Factory::Cookie());
    }

    /**
     * @return \Form_Controls_Hidden
     */
    public function &getCsrfControl()
    {
        return $this->_children[$this->csrfTokenAlias];
    }

    /**
     * @return \Lib\Protection\Csrf
     */
    public function getCsrf()
    {
        return $this->csrf;
    }

    /**
     * Reset protection token value in form
     */
    public function resetProtectionToken()
    {
        $token = $this->csrf->getToken($this->csrfTokenAlias);
        $this->getCsrfControl()->value($token);
    }

    /**
     * Recreate protection token
     */
    public function reloadProtection()
    {
        $this->csrf->removeToken($this->csrfTokenAlias);
        $this->getCsrfControl()->value($this->csrf->getToken($this->csrfTokenAlias));
    }

    /**
     * Zrusi kontrolu na CSRF
     */
    public function removeProtection()
    {
        $this->getCsrfControl()->removeRules();
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
        $this->getAttribute('method');
    }

    /**
     * Vyrenderuje Form
     * @param string|array $attributes
     * @return string
     */
    public function render($attributes = [])
    {
        $this->beforeRender();

        $this->addAttributes($attributes);

        return sprintf($this->_template, $this->renderAttributes(), $this->renderErrors(), $this->renderChildren());
    }

    /**
     * Renderuje start tag formulare a hidden elementy
     * @param string|array $attributes
     * @param bool $noChildren
     * @return string
     */
    public function renderStart($attributes = [], $noChildren = false)
    {
        $this->addAttributes($attributes);
        $return = sprintf($this->templateStart, $this->renderAttributes());
        if (false == $noChildren) {
            foreach ($this->_children as $child) {
                if ($child instanceof Form_Controls_Hidden) {
                    $return .= $child->renderInput() . N;
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
        foreach ($this->_children as $child) {
            if ($child instanceof Controls\AControl) {
                if (!empty($child->_ruleError)) {
                    if (!$child->wrappersErrors()) {
                        $child->addWrapperErrors($this->wrappersError);
                    }
                    $errors[$child->renderErrors()] = true;
                }
            }
        }
        if (!empty ($errors)) {
            $return = $this->wrappIt(implode('', array_keys($errors)), $this->wrappersErrors);

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
        foreach ($this->_children as $child) {
            if ($child instanceof Controls\AControl) {
                if (!empty($child->_ruleError)) {
                    if (!$child->wrappersErrors()) {
                        $child->addWrapperErrors($this->wrappersError);
                    }
                    $errors[$child->alias()] = $child->renderErrors();
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
        foreach ($this->_children as $alias => $child) {

            if ($child instanceof AHtmlElement) {
                if ($child instanceof Abstract_Form) {
                    if ($child->rendered()) {
                        continue;
                    }
                    if (($child->label() === null) && isset($this->_labels[$alias])) {
                        $child->label($this->_labels[$alias]);
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
                if ($child instanceof Form) {
                    $return .= $child->renderLabel() . $child->renderChildren() . "\n";
                } else if ($child instanceof Form_Controls_Hidden) {
                    $hidden .= $child->render() . "\n";
                } else {
                    $return .= $child->render() . "\n";
                }
            } else {
                $return .= $child;
            }
        }

        return $hidden . $this->wrappIt($return, $this->wrappersChildren);
    }

    /**
     * Propoji pole Context->post a Context->files s $this->children
     */
    public function setPostValues()
    {
        $context = Factory::Context();
        $var = $this->getMethod();
        $this->setValues(($context->{$var} + $context->files));
    }

    public function isSubmitted()
    {
        $context = Factory::Context();

        $var = $this->getMethod();

        if (isset($context->{$var}[$this->csrfTokenAlias])) {
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
            $this->setPostValues();
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

        if (isset($this->_children[$alias]) && !empty($errorText)) {
            $this->_children[$alias]->triggerError($errorText);
        }
    }

    /**
     * Vyresetuje priznak valid formulare<br />
     * a umozni tak zahajit validaci "na zelene louce"
     */
    public final function resetValid()
    {
        $this->isValid = true;
    }

    /**
     * Zjisti zda je formular validni
     * @return boolean
     */
    public final function isValid()
    {
        return ($this->validate() && $this->isValid);
    }

    /**
     * Validace formulare,<br />
     * probiha tak ze validuje vsechny childy a sam sebe
     * @return boolean
     */
    public function validate()
    {
        $validation = true;
        foreach ($this->_children as $child) {
            if (($child instanceof Controls\AControl) && !$child->validate()) {
                $validation = false;
            }
        }

        return ($validation && $this->isValid);
    }

    /**
     * Nastavi layout formulare
     * @param string $layoutName
     * @return $this
     * @throws FormsException
     */
    public function setLayout($layoutName = '')
    {
        if (!is_string($layoutName)) {
            throw new FormsException('Metoda setLayout vyzaduje jeden parametr typu string.');
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