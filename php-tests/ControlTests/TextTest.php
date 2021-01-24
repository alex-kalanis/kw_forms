<?php

namespace ControlTests;


use CommonTestClass;
use kalanis\kw_forms\Controls;


class TextTest extends CommonTestClass
{
    public function testInput(): void
    {
        $input = new Controls\Input();
        $input->set('text', 'myown', 'original', 'not to look');
        $this->assertEquals('<input value="original" type="text" name="myown" />', $input->renderInput());
        $input->setValue('jhgfd');
        $this->assertEquals('<input value="jhgfd" type="text" name="myown" />', $input->renderInput());
    }

    public function testText(): void
    {
        $input = new Controls\Text();
        $input->set('myown', 'original', 'not to look');
        $this->assertEquals('<input type="text" value="original" name="myown" />', $input->renderInput());
        $input->setValue('jhgfd');
        $this->assertEquals('<input type="text" value="jhgfd" name="myown" />', $input->renderInput());
    }

    public function testTextarea(): void
    {
        $input = new Controls\Textarea();
        $input->set('myown', 'original', 'not to look');
        $this->assertEquals('<textarea name="myown">original</textarea>', $input->renderInput());
        $input->setValue('jhgfd');
        $this->assertEquals('<textarea name="myown">jhgfd</textarea>', $input->renderInput());
    }

    public function testEmail(): void
    {
        $input = new Controls\Email();
        $input->set('myown', 'original', 'not to look');
        $this->assertEquals('<input type="email" value="original" name="myown" />', $input->renderInput());
        $input->setValue('jhgfd');
        $this->assertEquals('<input type="email" value="jhgfd" name="myown" />', $input->renderInput());
    }

    public function testPassword(): void
    {
        $input = new Controls\Password();
        $input->set('myown', 'not to look');
        $this->assertEquals('<input type="password" value="" name="myown" />', $input->renderInput());
        $input->setValue('jhgfd');
        $this->assertEquals('<input type="password" value="" name="myown" />', $input->renderInput());
    }

    public function testPhone(): void
    {
        $input = new Controls\Telephone();
        $input->set('myown', 'original', 'not to look');
        $this->assertEquals('<input type="tel" value="original" name="myown" />', $input->renderInput());
        $input->setValue('jhgfd');
        $this->assertEquals('<input type="tel" value="jhgfd" name="myown" />', $input->renderInput());
    }
}
