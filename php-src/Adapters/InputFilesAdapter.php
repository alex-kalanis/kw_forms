<?php

namespace kalanis\kw_forms\Adapters;


use kalanis\kw_forms\Exceptions\FormsException;
use kalanis\kw_input\Interfaces\IEntry;
use kalanis\kw_input\Interfaces\IInputs;


/**
 * Class InputFilesAdapter
 * @package kalanis\kw_forms\Adapters
 * @codeCoverageIgnore accessing remote libraries
 */
class InputFilesAdapter extends FilesAdapter
{
    protected $inputs = null;

    public function __construct(IInputs $inputs)
    {
        $this->inputs = $inputs;
    }

    public function loadEntries(string $inputType): void
    {
        $this->vars = $this->inputs->intoKeyObjectArray($this->inputs->getIn(null, [IEntry::SOURCE_FILES]));
    }

    public function current()
    {
        if ($this->valid()) {
            return $this->offsetGet($this->key);
        }
        throw new FormsException(sprintf('Unknown offset %s', $this->key));
    }
}
