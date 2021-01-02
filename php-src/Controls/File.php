<?php

namespace kalanis\kw_forms\Controls;


use kalanis\kw_forms\Exceptions\EntryException;
use kalanis\kw_forms\Interfaces;
use kalanis\kw_forms\Rules;


/**
 * Class File
 * @package kalanis\kw_forms\Controls
 * Render input for sending files
 */
class File extends AControl implements Interfaces\IValidateFile
{
    protected $templateInput = '<input type="file"%2$s />';

    /** @var Interfaces\IValidateFile|null */
    protected $value = null;

    protected function whichFactory(): Interfaces\IRuleFactory
    {
        return new Rules\File\Factory();
    }

    public function set(string $alias, string $label = ''): self
    {
        $this->setEntry($alias, null, $label);
        return $this;
    }

    public function renderInput($attributes = null): string
    {
        $this->addAttributes($attributes);
        $this->setAttribute('name', $this->getKey());
        return $this->wrapIt(sprintf($this->templateInput, null, $this->renderAttributes()), $this->wrappersInput);
    }

    public function setValue($value): TValue
    {
        if (!($value instanceof Interfaces\IValidateFile)) {
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

    public function getFile(): Interfaces\IValidateFile
    {
        $this->checkFile();
        return $this->value;
    }

    protected function checkFile(): void
    {
        if (empty($this->value)) {
            throw new EntryException(sprintf('Entry %s does not contains file', $this->getKey()));
        }
    }
}
