<?php

namespace kalanis\kw_forms\Entries;


use kalanis\kw_forms\Exceptions\EntryException;
use kalanis\kw_forms\Interfaces\IRuleFactory;
use kalanis\kw_forms\Interfaces\IValidateFile;
use kalanis\kw_forms\Rules;


class File extends Simple implements IValidateFile
{
    /** @var IValidateFile|null */
    protected $value = null;

    public function whichFactory(): IRuleFactory
    {
        return new Rules\File\Factory();
    }

    public function setValue($value): parent
    {
        if (!($value instanceof IValidateFile)) {
            throw new EntryException(sprintf('Set something other than file for entry %s', $this->getKey()));
        }
        $this->value = $value;
        return $this;
    }

    public function getValue(): string
    {
        $this->checkFile();
        return $this->value->getValue();
    }

    public function getMimeType(): string
    {
        $this->checkFile();
        return $this->value->getMimeType();
    }

    public function getTempName(): string
    {
        $this->checkFile();
        return $this->value->getTempName();
    }

    public function getError(): int
    {
        $this->checkFile();
        return $this->value->getError();
    }

    public function getSize(): int
    {
        $this->checkFile();
        return $this->value->getSize();
    }

    protected function checkFile(): void
    {
        if (empty($this->value)) {
            throw new EntryException(sprintf('Entry %s does not contains file', $this->getKey()));
        }
    }
}