<?php

namespace kalanis\kw_forms\examples;


use kalanis\kw_forms\Adapters\FilesAdapter;
use kalanis\kw_forms\Adapters\VarsAdapter;
use kalanis\kw_forms\Form;
use kalanis\kw_rules\Interfaces\IRules;


class UploadForm extends Form
{
    public function set(): self
    {
        $this->addFile('file', 'Uploaded file')
            ->addRule(IRules::FILE_RECEIVED, 'Must be send');
        $this->addSubmit('upload', 'Upload');
        return $this;
    }
}


$inputs = new VarsAdapter();
$inputs->loadEntries(VarsAdapter::SOURCE_POST);
$upload = new UploadForm('upload');
$upload->set();
$upload->setInputs($inputs, new FilesAdapter());

if ($upload->process('upload')) {
    $upload->getValues();
    // process things from form
}

$upload->render();
