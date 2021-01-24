<?php

namespace ControlTests;


use CommonTestClass;
use kalanis\kw_forms\Controls;


class DateTest extends CommonTestClass
{
    public function testDate(): void
    {
        $input = new Controls\DatePicker();
        $input->set('commit', 'original', 'not to look');
        $this->assertEquals('<input type="text" value="" class="datepicker" name="commit" />', $input->renderInput());
        $input->setValue(1333571265);
        $input->setDateFormat('Y-m-d H:i');
        $this->assertEquals('<input type="text" value="2012-04-04 20:27" class="datepicker" name="commit" />', $input->renderInput());
        $input->setValue('2010-08-18 22:33');
        $this->assertEquals('<input type="text" value="2010-08-18 22:33" class="datepicker" name="commit" />', $input->renderInput());
    }

    public function testRange(): void
    {
        $input = new Controls\DateRange();
        $input->set('myown', 'original', 'not to look');
        $this->assertEquals(' <input type="text" value="" class="datepicker" name="myown[]" /> '. PHP_EOL . ' <input type="text" value="" class="datepicker" name="myown[]" /> ', $input->renderInput());
    }
}
