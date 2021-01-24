<?php

namespace ControlTests;


use CommonTestClass;
use kalanis\kw_forms\Controls;


class SpecialTest extends CommonTestClass
{
    public function testHidden(): void
    {
        $input = new Controls\Hidden();
        $input->set('myown', 'original');
        $this->assertEquals('<input type="hidden" value="original" name="myown" />', $input->renderInput());
        $input->setValue('jhgfd');
        $this->assertEquals('<input type="hidden" value="jhgfd" name="myown" />', $input->renderInput());
    }

    public function testDescription(): void
    {
        $input = new Controls\Description();
        $input->setEntry('myown', 'original', 'not to look');
        $this->assertEquals('original ', $input->renderInput());
        $input->setValue('jhgfd');
        $this->assertEquals('jhgfd ', $input->renderInput());
    }

    public function testHtml(): void
    {
        $input = new Controls\Html();
        $input->setEntry('myown', 'original', 'not to look');
        $this->assertEquals('<span  name="myown">original</span>', $input->renderInput());
        $input->setValue('jhgfd');
        $this->assertEquals('<span  name="myown">jhgfd</span>', $input->renderInput());
    }
}
