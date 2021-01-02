<?php

namespace kalanis\kw_forms\Controls;


use ArrayAccess;
use kalanis\kw_forms\Exceptions\FormsException;


class Factory
{
    protected static $map = [
        'input' => '\kalanis\kw_forms\Controls\Input',
        'text' => '\kalanis\kw_forms\Controls\Text',
        'textarea' => '\kalanis\kw_forms\Controls\Textarea',
        'email' => '\kalanis\kw_forms\Controls\Email',
        'pass' => '\kalanis\kw_forms\Controls\Password',
        'password' => '\kalanis\kw_forms\Controls\Password',
        'phone' => '\kalanis\kw_forms\Controls\Telephone',
        'telephone' => '\kalanis\kw_forms\Controls\Telephone',
        'check' => 'Checkbox',
        'checkbox' => 'Checkbox',
        'select' => 'Select',
        'selectbox' => 'Selectbox',
//        'radio' => 'Radio',
//        'radiobutton' => 'Radio',
        'hidden' => '\kalanis\kw_forms\Controls\Hidden',
        'date' => '\kalanis\kw_forms\Controls\DatePicker',
        'datetime' => '\kalanis\kw_forms\Controls\DateTimePicker',
        'daterange' => '\kalanis\kw_forms\Controls\DateRange',
        'description' => '\kalanis\kw_forms\Controls\Description',
        'desc' => '\kalanis\kw_forms\Controls\Description',
        'html' => '\kalanis\kw_forms\Controls\Html',
        'file' => '\kalanis\kw_forms\Controls\File',
        'button' => '\kalanis\kw_forms\Controls\Button',
        'accept' => '\kalanis\kw_forms\Controls\Submit',
        'submit' => '\kalanis\kw_forms\Controls\Submit',
        'cancel' => '\kalanis\kw_forms\Controls\Reset',
        'reset' => '\kalanis\kw_forms\Controls\Reset',
        'captchadis' => '\kalanis\kw_forms\Controls\Security\Captcha\Disabled',
        'captchatext' => '\kalanis\kw_forms\Controls\Security\Captcha\Text',
        'captchaplus' => '\kalanis\kw_forms\Controls\Security\Captcha\Numerical',
        'nocaptcha' => '\kalanis\kw_forms\Controls\Security\Captcha\NoCaptcha',
        'csrf' => '\kalanis\kw_forms\Controls\Security\Csrf',
        'multisend' => '\kalanis\kw_forms\Controls\Security\MultiSend',
    ];

    /**
     * Factory for getting classes of each input available by kw_forms
     * @param string $type
     * @return AControl
     * @throws FormsException
     */
    public function getControl(string $type): AControl
    {
        $type = strtolower($type);
        if (isset(static::$map[$type])) {
            $class = static::$map[$type];
            return new $class();
        }
        throw new FormsException(sprintf('Unknown type %s ', $type));
    }

    /**
     * Get simple form input
     * @param string $alias
     * @param string $label
     * @param string $value
     * @param array|string $attributes
     * @return Input
     */
    public function getInput(string $alias, ?string $label = null, $value = null, $attributes = [])
    {
        $input = new Input();
        $input->set($alias, $value, $label)->addAttributes($attributes);
        return $input;
    }

    /**
     * Get form input for text
     * @param string $alias
     * @param string $label
     * @param string $value
     * @param array|string $attributes
     * @return Text
     */
    public function getText(string $alias, ?string $label = null, $value = null, $attributes = [])
    {
        $text = new Text();
        $text->set($alias, $value, $label)->addAttributes($attributes);
        return $text;
    }

    /**
     * Get form input for email
     * @param string $alias
     * @param string $label
     * @param string $value
     * @param array|string $attributes
     * @return Email
     */
    public function getEmail(string $alias, ?string $label = null, $value = null, $attributes = [])
    {
        $mail = new Email();
        $mail->set($alias, $value, $label)->addAttributes($attributes);
        return $mail;
    }

    /**
     * Get form input for password
     * @param string $alias
     * @param string $label
     * @param array|string $attributes
     * @return Password
     */
    public function getPassword(string $alias, ?string $label = null, $attributes = [])
    {
        $pass = new Password();
        $pass->set($alias, $label)->addAttributes($attributes);
        return $pass;
    }

    /**
     * Get hidden form input
     * @param string $alias
     * @param mixed $value
     * @param array|string $attributes
     * @return Hidden
     */
    public function getHidden(string $alias, ?string $value = null, $attributes = [])
    {
        $hidden = new Hidden();
        $hidden->set($alias, $value)->addAttributes($attributes);
        return $hidden;
    }

    /**
     * @param string $alias
     * @param string|null $label
     * @param string|null $value
     * @param string[] $attributes
     * @return DatePicker
     */
    public function getDatePicker(string $alias, ?string $label = null, $value = null, $attributes = [])
    {
        $date = new DatePicker();
        $date->set($alias, $value, $label)->addAttributes($attributes);
        return $date;
    }

    /**
     * @param string $alias
     * @param string|null $label
     * @param string|null $value
     * @param string[] $attributes
     * @return DateTimePicker
     */
    public function getDateTimePicker(string $alias, ?string $label = null, $value = null, $attributes = [])
    {
        $date = new DateTimePicker();
        $date->set($alias, $value, $label)->addAttributes($attributes);
        return $date;
    }

    /**
     * @param string $alias
     * @param string|null $label
     * @param string|null $value
     * @param string[] $attributes
     * @return DateRange
     */
    public function getDateRange(string $alias, ?string $label = null, $value = null, $attributes = [])
    {
        $date = new DateRange();
        $date->set($alias, $value, $label)->addAttributes($attributes);
        return $date;
    }

    /**
     * @param string $alias
     * @param string|null $label
     * @param string|null $value
     * @return Description
     */
    public function getDescription(string $alias, ?string $label = null, $value = null)
    {
        $desc = new Description();
        $desc->setEntry($alias, $value, $label);
        return $desc;
    }

    /**
     * Prida do Formu input typu Html
     * @param string $alias
     * @param string $label
     * @param string $value
     * @param array|string $attributes
     * @return Html
     */
    public function getHtml(string $alias, ?string $label = null, $value = null, $attributes = [])
    {
        $html = new Html();
        $html->setEntry($alias, $value, $label)->addAttributes($attributes);
        return $html;
    }

    /**
     * prida do Formu textareu
     * @param string $alias
     * @param string $label
     * @param string $value
     * @param array|string $attributes
     * @return Textarea
     */
    public function getTextarea(string $alias, ?string $label = null, $value = null, $attributes = [])
    {
        $text = new Textarea();
        $text->setEntry($alias, $value, $label)->addAttributes($attributes);
        return $text;
    }

    /**
     * Prida do Formu select
     * @param string $alias
     * @param string $label
     * @param array|string $value
     * @param array|string $children
     * @param array|string $attributes
     * @return \Form_Controls_Select
     */
    public function getSelect(string $alias, ?string $label = null, $value = null, $children = [], $attributes = [])
    {
        $select = new \Form_Controls_Select($alias, $value, $label, $children);
        $select->addAttributes($attributes);
        return $select;
    }

    /**
     * Prida do formu radioset
     * @param string $alias
     * @param string $label
     * @param string $value
     * @param array $children
     * @param array $attributes
     * @return \Form_Controls_RadioSet
     */
    public function getRadios(string $alias, ?string $label = null, $value = null, $children = [], $attributes = [])
    {
        $radio = new \Form_Controls_RadioSet($alias, $value, $label, $children);
        $radio->addAttributes($attributes);
        return $radio;
    }

    /**
     * Prida do Formu input typu checkbox
     * @param string $alias
     * @param string $label
     * @param boolean $checked
     * @param string|int $value
     * @param array|string $attributes
     * @return \Form_Controls_Checkbox
     */
    public function getCheckbox(string $alias, ?string $label = null, $checked = null, $value = 1, $attributes = [])
    {
        $check = new \Form_Controls_Checkbox($alias, $value, $label);
        $check->checked($checked);
        $check->addAttributes($attributes);
        return $check;
    }

    /**
     * Prida do Formu input typu checkbox switch
     * @param string $alias
     * @param string $label
     * @param boolean $checked
     * @param string|int $value
     * @param array|string $attributes
     * @return \Form_Controls_Checkbox_Switch
     */
    public function getCheckboxSwitch(string $alias, ?string $label = null, $checked = null, $value = 1, $attributes = [])
    {
        $switch = new \Form_Controls_Checkbox_Switch($alias, $value, $label);
        $switch->checked($checked);
        $switch->addAttributes($attributes);
        return $switch;
    }

    /**
     * Prida do Formu skupinu checkboxu
     * @param string $alias
     * @param array|string $label
     * @param array|string $checked
     * @param array $children
     * @param array|string $attributes
     * @return \Form_Controls_Checkboxes
     */
    public function getCheckboxes(string $alias, ?string $label = null, $checked = [], array $children = [], $attributes = [])
    {
        $check = new \Form_Controls_Checkboxes($alias, $checked, $label, $children);
        $check->addAttributes($attributes);
        return $check;
    }

    /**
     * Prida do Formu input typu file
     * @param string $alias
     * @param string $label
     * @param array|string $attributes
     * @return File
     */
    public function getFile(string $alias, ?string $label = null, $attributes = [])
    {
        $file = new File();
        $file->set($alias, $label);
        $file->addAttributes($attributes);
        return $file;
    }

    /**
     * Prida do Formu skupinu file inputu
     * @param string $alias
     * @param array|string $label
     * @param array $children
     * @param array|string $attributes
     * @return \Form_Controls_Files
     */
    public function getFiles(string $alias, ?string $label = null, $children = [], $attributes = [])
    {
        $file = new \Form_Controls_Files($alias, null, $label, $children);
        $file->addAttributes($attributes);
        return $file;
    }

    /**
     * Get form input type Simple Button
     * @param string $alias
     * @param string $label
     * @param array|string $attributes
     * @return Button
     */
    public function getButton(string $alias, ?string $label = null, $attributes = [])
    {
        $button = new Button();
        $button->set($alias, $label)->addAttributes($attributes);
        return $button;
    }

    /**
     * Get form input type Reset
     * @param string $alias
     * @param string $label
     * @param array|string $attributes
     * @return Reset
     */
    public function getReset(string $alias, ?string $label = null, $attributes = [])
    {
        $reset = new Reset();
        $reset->set($alias, $label)->addAttributes($attributes);
        return $reset;
    }

    /**
     * Get form input type Submit
     * @param string $alias
     * @param string $label
     * @param array|string $attributes
     * @return Submit
     */
    public function getSubmit(string $alias, ?string $label = null, $attributes = [])
    {
        $submit = new Submit();
        $submit->set($alias, $label)->addAttributes($attributes);
        return $submit;
    }

    /**
     * Get form input type Submit
     * @param string $alias
     * @param ArrayAccess $cookie
     * @param string $errorMessage
     * @param array|string $attributes
     * @return Security\Csrf
     */
    public function getCsrf(string $alias, ArrayAccess $cookie, string $errorMessage, $attributes = [])
    {
        $submit = new Security\Csrf();
        $submit->setHidden($alias, $cookie, $errorMessage)->addAttributes($attributes);
        return $submit;
    }

    /**
     * Add empty captcha
     * @param string $alias
     * @return Security\Captcha\Disabled
     */
    public function getCaptchaDisabled(string $alias)
    {
        $captcha = new Security\Captcha\Disabled();
        $captcha->setEntry($alias);
        return $captcha;
    }

    /**
     * Add simple image-to-text captcha
     * @param string $alias
     * @param ArrayAccess $session
     * @param string $errorMessage
     * @return Security\Captcha\Text
     */
    public function getCaptchaText(string $alias, ArrayAccess &$session, string $errorMessage = 'Captcha mismatch')
    {
        $captcha = new Security\Captcha\Text();
        $captcha->set($alias, $session, $errorMessage);
        return $captcha;
    }

    /**
     * Add captcha check with mathematical operation
     * @param string $alias
     * @param ArrayAccess $session
     * @param string $errorMessage
     * @return Security\Captcha\Numerical
     */
    public function getCaptchaMath(string $alias, ArrayAccess &$session, string $errorMessage = 'Captcha mismatch')
    {
        $captcha = new Security\Captcha\Numerical();
        $captcha->set($alias, $session, $errorMessage);
        return $captcha;
    }

    /**
     * Add captcha check with colourful text fill
     * @param string $alias
     * @param ArrayAccess $session
     * @param string $errorMessage
     * @return Security\Captcha\ColourfulText
     */
    public function getCaptchaColour(string $alias, ArrayAccess &$session, string $errorMessage = 'Captcha mismatch')
    {
        $captcha = new Security\Captcha\ColourfulText();
        $captcha->set($alias, $session, $errorMessage);
        return $captcha;
    }

    /**
     * Add captcha check via service ReCaptcha-NoCaptcha
     * @param string $alias
     * @param string $publicKey
     * @param string $privateKey
     * @param string $errorMessage
     * @return Security\Captcha\Nocaptcha
     */
    public function getNocaptcha(string $alias, string $publicKey, string $privateKey, string $errorMessage = 'The NoCAPTCHA wasn\'t entered correctly. Please try it again.')
    {
        $recaptcha = new Security\Captcha\Nocaptcha();
        $recaptcha->set($alias, $privateKey, $publicKey, $errorMessage);
        return $recaptcha;
    }
}
