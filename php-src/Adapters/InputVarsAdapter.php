<?php

namespace kalanis\kw_forms\Adapters;


use kalanis\kw_forms\Exceptions\FormsException;
use kalanis\kw_input\Interfaces\IEntry;
use kalanis\kw_input\Interfaces\IInputs;


class InputVarsAdapter extends VarsAdapter
{
    protected $inputs = null;

    public function __construct(IInputs $inputs)
    {
        $this->inputs = $inputs;
    }

    public function loadEntries(string $inputType): void
    {
        if (self::INPUT_POST == $inputType) {
            $this->vars = $this->inputs->intoKeyObjectArray($this->inputs->getIn(null, [IEntry::SOURCE_POST]));
        } elseif (self::INPUT_GET == $inputType) {
            $this->vars = $this->inputs->intoKeyObjectArray($this->inputs->getIn(null, [IEntry::SOURCE_GET]));
        } else {
            throw new FormsException(sprintf('Unknown input type - %s', $inputType));
        }
    }

    public function getValue()
    {
        return $this->current()->getValue();
    }

    public function current()
    {
        if ($this->valid()) {
            return $this->offsetGet($this->key);
        }
        throw new FormsException(sprintf('Unknown offset %s', $this->key));
    }
}