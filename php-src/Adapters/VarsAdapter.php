<?php

namespace kalanis\kw_forms\Adapters;


use kalanis\kw_forms\Exceptions\FormsException;
use kalanis\kw_forms\Interfaces\IInputs;


class VarsAdapter extends AAdapter
{
    public function loadEntries(string $inputType): void
    {
        if (IInputs::INPUT_POST == $inputType) {
            $this->vars = $this->loadVars($_POST);
        } elseif (IInputs::INPUT_GET == $inputType) {
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