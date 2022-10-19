<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_forms\Controls;
use kalanis\kw_forms\Form\TControl;
use kalanis\kw_forms\Form\TMethod;
use kalanis\kw_input\Interfaces\IEntry;
use kalanis\kw_templates\HtmlElement\TAttributes;


class BasicTest extends CommonTestClass
{
    /**
     * @param string $type
     * @param array $params
     * @param string $instance
     * @dataProvider factoryProvider
     */
    public function testAddingTrait(string $type, array $params, string $instance): void
    {
        $factory = new Control();
        $control = call_user_func_array([$factory, $type], $params);
        $this->assertInstanceOf($instance, $control);
    }

    public function factoryProvider(): array
    {
        $outside = new \MockArray();
        return [
            ['addInput', ['any', 'simple'], Controls\Input::class],
            ['addInput', ['text', 'combine'], Controls\Input::class],
            ['addText', ['name', 'label', 'value'], Controls\Text::class],
            ['addEmail', ['from', 'sending'], Controls\Email::class],
            ['addPassword', ['pass', 'you know'], Controls\Password::class],
            ['addHidden', ['hide', 'me'], Controls\Hidden::class],
            ['addDatePicker', ['date', 'yesterday'], Controls\DatePicker::class],
            ['addDateTimePicker', ['meetup', 'tomorrow'], Controls\DateTimePicker::class],
            ['addDateRange', ['rage'], Controls\DateRange::class],
            ['addDescription', ['out', 'not hard'], Controls\Description::class],
            ['addHtml', ['in', 'burning'], Controls\Html::class],
            ['addTextarea', ['more', 'about other things'], Controls\Textarea::class],
            ['addSelect', ['name', 'label', 'value'], Controls\Select::class],
            ['addSelectList', ['name', 'label', ['check1' => 'foo', 'check2' => 'bar', 'check3' => 'baz', ], 3], Controls\SelectList::class],
            ['addRadios', ['name', 'label', 'value'], Controls\RadioSet::class],
            ['addCheckbox', ['name', 'label', 'value'], Controls\Checkbox::class],
            ['addCheckboxSwitch', ['name', 'label', 'value'], Controls\CheckboxSwitch::class],
            ['addCheckboxes', ['name', 'label', ['check1' => 'foo', 'check2' => 'bar', 'check3' => 'baz', ], ['check1', 'check3', 'check4']], Controls\Checkboxes::class],
            ['addFile', ['uploaded', 'file'], Controls\File::class],
            ['addFiles', ['passed', 'files'], Controls\Files::class],
            ['addButton', ['click', 'here'], Controls\Button::class],
            ['addReset', ['reload', 'reload'], Controls\Reset::class],
            ['addSubmit', ['submit', 'commit'], Controls\Submit::class],
            ['addMultiSend', ['outMulti', &$outside, 'our end'], Controls\Security\MultiSend::class],
            ['addCaptchaDisabled', ['captchaDis'], Controls\Security\Captcha\Disabled::class],
            ['addCaptchaText', ['captchaTxt', &$outside], Controls\Security\Captcha\Text::class],
            ['addCaptchaMath', ['captchaNum', &$outside], Controls\Security\Captcha\Numerical::class],
            ['addCaptchaColour', ['captchaCol', &$outside], Controls\Security\Captcha\ColourfulText::class],
            ['addNocaptcha', ['captcha'], Controls\Security\Captcha\NoCaptcha::class],
        ];
    }

    public function testMethod(): void
    {
        $method = new Method();
        $this->assertEmpty($method->getMethod());
        $method->setMethod(IEntry::SOURCE_ENV); // bad one
        $this->assertEmpty($method->getMethod());
        $method->setMethod(IEntry::SOURCE_GET); // good one
        $this->assertNotEmpty($method->getMethod());
        $this->assertEquals(IEntry::SOURCE_GET, $method->getMethod());
    }
}


class Method
{
    use TMethod;
    use TAttributes;
}


class Control
{
    use TControl;

    public function addControlDefaultKey(Controls\AControl $control): void
    {
        // nothing need to be implemented
    }

    public function setAttribute(string $name, string $value): void
    {
        // nothing need to be implemented
    }
}
