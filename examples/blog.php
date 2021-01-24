<?php

namespace kalanis\kw_forms\examples;


use kalanis\kw_forms\Adapters\VarsAdapter;
use kalanis\kw_forms\Form;
use kalanis\kw_rules\Interfaces\IRules;


class BlogForm extends Form
{
    public function set(): self
    {
        $this->addTextarea('content', 'Your message')
            ->addRule(IRules::IS_NOT_EMPTY, 'Must be filled');
        $this->addSubmit('save', 'Save');
        return $this;
    }
}


$inputs = new VarsAdapter();
$inputs->loadEntries(VarsAdapter::SOURCE_POST);
$blog = new BlogForm('blog');
$blog->set();
$blog->setInputs($inputs);

if ($blog->process('save')) {
    $blog->getValues();
    // process things from form
}

$blog->render();
