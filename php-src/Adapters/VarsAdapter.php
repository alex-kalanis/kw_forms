<?php

namespace kalanis\kw_forms\Adapters;


use kalanis\kw_forms\Exceptions\FormsException;

class VarsAdapter extends AAdapter
{
    public function loadEntries(string $inputType): void
    {
        if (self::INPUT_POST == $inputType) {
            $this->vars = $this->loadVars($_POST);
        } elseif (self::INPUT_GET == $inputType) {
            $this->vars = $this->loadVars($_GET);
        } else {
            throw new FormsException(sprintf('Unknown input type - %s', $inputType));
        }
    }

    protected function loadVars(&$array): array
    {
        $result = [];
        foreach ($array as $postedKey => $posted) {
            $result[$this->removeNullBytes($postedKey)] = $this->removeNullBytes($posted);
        }
        return $result;
    }
}