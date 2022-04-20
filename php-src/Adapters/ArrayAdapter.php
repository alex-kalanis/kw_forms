<?php

namespace kalanis\kw_forms\Adapters;


use kalanis\kw_input\Interfaces\IEntry;


/**
 * Class ArrayAdapter
 * @package kalanis\kw_forms\Adapters
 */
class ArrayAdapter extends AAdapter
{
    protected $inputs = null;
    protected $inputType = IEntry::SOURCE_GET;

    public function __construct(array $inputs)
    {
        $this->inputs = $inputs;
    }

    public function loadEntries(string $inputType): void
    {
        $result = [];
        foreach ($this->inputs as $postedKey => &$posted) {
            $result[$this->removeNullBytes($postedKey)] = $this->removeNullBytes($posted);
        }
        $this->vars = $result;
        $this->inputType = $inputType;
    }

    public function getSource(): string
    {
        return $this->inputType;
    }
}
