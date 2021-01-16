<?php

namespace kalanis\kw_forms\Adapters;


use kalanis\kw_forms\Exceptions\FormsException;
use kalanis\kw_rules\Interfaces;
use kalanis\kw_rules\Rules;


class FilesAdapter extends AAdapter implements Interfaces\IValidateFile
{
    public function loadEntries(string $inputType): void
    {
        $this->vars = $this->loadVars($_FILES);
    }

    protected function whichFactory(): Interfaces\IRuleFactory
    {
        return new Rules\File\Factory();
    }

    protected function loadVars(&$array): array
    {
        $result = [];
        foreach ($array as $postedKey => $posted) {
            if (is_array($posted['name'])) {
                foreach ($posted['name'] as $key => $value) {
                    $key = sprintf('%s[%s]', $this->removeNullBytes($postedKey), $this->removeNullBytes($key));
                    $entry = [
                        'value' => $this->removeNullBytes($value),
                        'temp' => $posted['tmp_name'][$key],
                        'mime' => $posted['type'][$key],
                        'error' => intval($posted['error'][$key]),
                        'size' => intval($posted['size'][$key]),
                    ];
                    $result[$key] = $entry;
                }
            } else {
                $entry = [
                    'value' => $this->removeNullBytes($posted['name']),
                    'temp' => $posted['tmp_name'],
                    'mime' => $posted['type'],
                    'error' => intval($posted['error']),
                    'size' => intval($posted['size']),
                ];
                $result[$this->removeNullBytes($postedKey)] = $entry;
            }
        }
        return $result;
    }

    public function current()
    {
        if ($this->valid()) {
            return $this->offsetGet($this->key);
        }
        throw new FormsException(sprintf('Unknown offset %s', $this->key));
    }

    public function getValue()
    {
        return $this->current()['value'];
    }

    public function getMimeType(): string
    {
        return $this->current()['mime'];
    }

    public function getTempName(): string
    {
        return $this->current()['temp'];
    }

    public function getError(): int
    {
        return $this->current()['error'];
    }

    public function getSize(): int
    {
        return $this->current()['size'];
    }
}