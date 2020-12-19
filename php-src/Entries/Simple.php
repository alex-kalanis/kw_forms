<?php

namespace kalanis\kw_forms\Entries;


use kalanis\kw_forms\Interfaces\IRuleFactory;
use kalanis\kw_forms\Interfaces\IValidate;
use kalanis\kw_forms\Rules;


class Simple implements IValidate
{
    use TKey;
    use TValidate;
    use TValue;

    public function whichFactory(): IRuleFactory
    {
        return new Rules\Factory();
    }
}