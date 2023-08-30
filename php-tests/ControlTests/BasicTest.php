<?php

namespace ControlTests;


use CommonTestClass;
use kalanis\kw_forms\Controls;
use kalanis\kw_forms\Exceptions\FormsException;
use kalanis\kw_forms\Exceptions\RenderException;
use kalanis\kw_forms\Interfaces;
use kalanis\kw_rules\Interfaces\IRules;
use kalanis\kw_rules\Validate;
use kalanis\kw_templates\Interfaces\IHtmlElement;
use kalanis\kw_templates\HtmlElement\TAttributes;
use kalanis\kw_templates\HtmlElement\THtmlElement;


class BasicTest extends CommonTestClass
{
    /**
     * @param string $type
     * @param string $instance
     * @throws FormsException
     * @dataProvider factoryProvider
     */
    public function testFactory(string $type, string $instance): void
    {
        $factory = new Controls\Factory();
        $control = $factory->getControl($type);
        $this->assertInstanceOf($instance, $control);
    }

    public function factoryProvider(): array
    {
        return [
            ['input', Controls\Input::class],
            ['text', Controls\Text::class],
            ['textarea', Controls\Textarea::class],
            ['email', Controls\Email::class],
            ['pass', Controls\Password::class],
            ['password', Controls\Password::class],
            ['phone', Controls\Telephone::class],
            ['telephone', Controls\Telephone::class],
            ['chk', Controls\Checkbox::class],
            ['check', Controls\Checkbox::class],
            ['checkbox', Controls\Checkbox::class],
            ['checkboxswitch', Controls\CheckboxSwitch::class],
            ['select', Controls\Select::class],
            ['selectbox', Controls\Select::class],
            ['radio', Controls\Radio::class],
            ['radioset', Controls\RadioSet::class],
            ['radiobutton', Controls\Radio::class],
            ['hidden', Controls\Hidden::class],
            ['date', Controls\DatePicker::class],
            ['datetime', Controls\DateTimePicker::class],
            ['daterange', Controls\DateRange::class],
            ['description', Controls\Description::class],
            ['desc', Controls\Description::class],
            ['html', Controls\Html::class],
            ['file', Controls\File::class],
            ['button', Controls\Button::class],
            ['accept', Controls\Submit::class],
            ['submit', Controls\Submit::class],
            ['cancel', Controls\Reset::class],
            ['reset', Controls\Reset::class],
            ['captchadis', Controls\Security\Captcha\Disabled::class],
            ['captchatext', Controls\Security\Captcha\Text::class],
            ['captchaplus', Controls\Security\Captcha\Numerical::class],
            ['nocaptcha', Controls\Security\Captcha\NoCaptcha::class],
            ['csrf', Controls\Security\Csrf::class],
            ['multisend', Controls\Security\MultiSend::class],
        ];
    }

    /**
     * @param mixed $type
     * @throws FormsException
     * @dataProvider factoryDieProvider
     */
    public function testFactoryDie($type): void
    {
        $factory = new Controls\Factory();
        $this->expectException(FormsException::class);
        $factory->getControl(strval($type));
    }

    public function factoryDieProvider(): array
    {
        return [
            [Controls\Input::class],
            ['something'],
            [123456],
        ];
    }

    /**
     * @throws FormsException
     */
    public function testShittySettingInstance(): void
    {
        $factory = new XFactory();
        $this->expectException(FormsException::class);
        $factory->getControl('not_instance');
    }

    /**
     * @throws FormsException
     */
    public function testShittySettingClass(): void
    {
        $factory = new XFactory();
        $this->expectException(FormsException::class);
        $factory->getControl('not_class');
    }

    public function testKey(): void
    {
        $key = new Key();
        $this->assertEmpty($key->getKey());
        $key->setKey('sdfghj');
        $this->assertNotEmpty($key->getKey());
        $this->assertEquals('sdfghj', $key->getKey());
    }

    public function testValue(): void
    {
        $value = new Value();
        $this->assertEmpty($value->getValue());
        $value->setValue('sdfghj');
        $this->assertNotEmpty($value->getValue());
        $this->assertEquals('sdfghj', $value->getValue());
    }

    public function testLabel(): void
    {
        $label = new Label();
        $this->assertEmpty($label->getLabel());
        $label->setLabel('yxcvbnm');
        $this->assertNotEmpty($label->getLabel());
        $this->assertEquals('yxcvbnm', $label->getLabel());
    }

    public function testTmplError(): void
    {
        $error = new TemplateError();
        $error->setTemplateError('');
        $this->assertEmpty($error->getTemplateError());
        $error->setTemplateError('lkjhgfdsa');
        $this->assertNotEmpty($error->getTemplateError());
        $this->assertEquals('lkjhgfdsa', $error->getTemplateError());
    }

    public function testChecked(): void
    {
        $checked = new Checked();
        $this->assertEmpty($checked->getValue());
        $checked->setValue('yxcvbnm');
        $this->assertEquals('rjgvnsg', $checked->getValue());
        $checked->setValue('none');
        $this->assertEmpty($checked->getValue());
    }

    public function testSelected(): void
    {
        $selected = new Selected();
        $this->assertEmpty($selected->getValue());
        $selected->setValue('rjgvnsg');
        $this->assertEquals('rjgvnsg', $selected->getValue());
        $selected->setValue('yxcvbnm');
        $this->assertEmpty($selected->getValue());
        $selected->setValue('none');
        $this->assertEmpty($selected->getValue());
    }

    public function testMultiple(): void
    {
        $multiple = new Multiple();
        $this->assertFalse($multiple->getMultiple());
        $multiple->setMultiple('yxcvbnm');
        $this->assertTrue($multiple->getMultiple());
        $multiple->setMultiple('none');
        $this->assertFalse($multiple->getMultiple());
    }

    /**
     * @throws RenderException
     */
    public function testControl(): void
    {
        $validate = new Validate();

        $input = new Control();
        $input->addRule(IRules::IS_NOT_EMPTY, 'still empty!'); // factory, check for errors

        $this->assertEmpty($input->getLabel());
        $this->assertEmpty($input->renderLabel());
        $this->assertEquals(0, $input->count());

        $input->setLabel('not to look');
        $this->assertEquals('<label for="">not to look</label>', $input->renderLabel());
        $input->setAttribute('id', 'poiu');
        $this->assertEquals('<label for="poiu">not to look</label>', $input->renderLabel());

        $validate->validate($input); // check after init

        $this->assertEquals('still empty!', $input->renderErrors($validate->getErrors()[$input->getKey()])); // got errors

        $input->setValue('jhgfd');

        $validate->validate($input); // check after fill

        $this->assertEmpty($input->renderErrors($validate->getErrors())); // no errors
    }

    /**
     * @throws RenderException
     */
    public function testWrapperInherit(): void
    {
        $wrappers = new Control();
        $wrappers->resetWrappers();
        $this->assertEmpty($wrappers->wrappers());
        $this->assertEmpty($wrappers->wrappersLabel());
        $this->assertEmpty($wrappers->wrappersInput());
        $this->assertEmpty($wrappers->wrappersChild());
        $this->assertEmpty($wrappers->wrappersChildren());
        $this->assertEmpty($wrappers->wrappersError());
        $this->assertEmpty($wrappers->wrappersErrors());

        $wrappers->addWrapper('span', ['style' => 'width:100em']);
        $wrappers->addWrapperLabel('div');
        $wrappers->addWrapperInput('div');
        $wrappers->addWrapperChild('span');
        $wrappers->addWrapperChildren(new Html(), ['class' => 'wat']);
        $wrappers->addWrapperError(['span', 'span']);
        $wrappers->addWrapperErrors('div');

        $sub = new Wrappers();
        $wrappers->inherit($sub);

        $sub->wrapping('div', $sub->wrappersInput());
    }

    /**
     * @throws RenderException
     */
    public function testWrapperObject(): void
    {
        $wrappers = new Wrappers();
        $wrappers->resetWrappers();
        $this->assertEmpty($wrappers->wrappersLabel());
        $wrappers->addWrapperLabel('div');
        $wrappers->wrapping('div', new Html());
    }

    /**
     * @throws RenderException
     */
    public function testWrapperDie(): void
    {
        $wrappers = new Wrappers();
        $wrappers->resetWrappers();
        $this->assertEmpty($wrappers->wrappersLabel());
        $wrappers->addWrapperLabel('div');
        $this->expectException(RenderException::class);
        $wrappers->wrapping('div', 123456);
    }
}


class Key
{
    use Controls\TKey;
}


class Value
{
    use Controls\TValue;
}


class Label
{
    use Controls\TLabel;
}


class TemplateError
{
    use Controls\TTemplateError;
}


class Checked
{
    use Controls\TChecked;
    use TAttributes;

    protected $originalValue = 'rjgvnsg';
}


class Selected
{
    use Controls\TSelected;
    use TAttributes;

    protected $originalValue = 'rjgvnsg';
}


class Multiple
{
    use Controls\TMultiple;
    use TAttributes;

    protected $originalValue = 'rjgvnsg';
    protected $children = [];
}


class Wrappers implements Interfaces\IWrapper, IHtmlElement
{
    use THtmlElement;
    use Controls\TWrappers;

    /**
     * @param string $string
     * @param mixed $wrappers
     * @throws RenderException
     * @return string
     */
    public function wrapping(string $string, $wrappers): string
    {
        return $this->wrapIt($string, $wrappers);
    }

    public function count(): int
    {
        return 0;
    }
}


class Html implements IHtmlElement
{
    use THtmlElement;

    public function count(): int
    {
        return 0;
    }
}


class Control extends Controls\AControl
{
}


class XFactory extends Controls\Factory
{
    /** @var array<string, string> */
    protected static $map = [
        'text' => Controls\Text::class,
        'not_instance' => \stdClass::class,
        'not_class'    => 'this_is_not_a_class',
    ];
}
