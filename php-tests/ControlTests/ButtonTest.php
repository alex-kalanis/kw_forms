<?php

namespace ControlTests;


use CommonTestClass;
use kalanis\kw_forms\Controls;


class ButtonTest extends CommonTestClass
{
    public function testButton(): void
    {
        $input = new Controls\Button();
        $input->set('commit', 'myown', 'original');
        $this->assertEquals('<input type="button" value="original" name="commit" />', $input->renderInput());
        $input->setValue('jhgfd');
        $this->assertEquals('<input type="button" value="jhgfd" name="commit" />', $input->renderInput());
    }

    public function testButton2(): void
    {
        $input = new Controls\Button();
        $input->set('myown');
        $this->assertEquals('<input type="button" value="myown" name="button" />', $input->renderInput());
        $input->set('myown', '');
        $this->assertEquals('<input type="button" value="myown" name="myown" />', $input->renderInput());
    }

    public function testSubmit(): void
    {
        $input = new Controls\Submit();
        $input->set('myown', 'original', 'not to look');
        $this->assertEquals('<input type="submit" value="" name="myown" />', $input->renderInput());
        $input->setValue('jhgfd');
        $this->assertEquals('<input type="submit" value="not to look" name="myown" />', $input->renderInput());
        $input->setValue(null);
        $this->assertEquals('<input type="submit" value="" name="myown" />', $input->renderInput());
    }

    public function testReset(): void
    {
        $input = new Controls\Reset();
        $input->set('myown', 'not to look', 'original');
        $this->assertEquals('<input type="reset" value="original" name="myown" />', $input->renderInput());
        $input->set('myown', '');
        $this->assertEquals('<input type="reset" value="myown" name="myown" />', $input->renderInput());
    }
}
