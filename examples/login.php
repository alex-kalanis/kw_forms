<?php

namespace kalanis\kw_forms\examples;


use kalanis\kw_forms\Adapters\CookieAdapter;
use kalanis\kw_forms\Adapters\VarsAdapter;
use kalanis\kw_forms\Form;
use kalanis\kw_rules\Interfaces\IRules;


class LoginForm extends Form
{
    public function set(\ArrayAccess $cookie): self
    {
        $this->addText('user', 'User name')
            ->addRule(IRules::IS_NOT_EMPTY, 'Must be filled');
        $pass = $this->addPassword('pass', 'Password');
        $pass->addRule(IRules::IS_NOT_EMPTY, 'Must be filled');
        $pass->addRule(IRules::SAFE_EQUALS_PASS, 'Must equals following', 'pass from somewhere');
        $this->addCsrf('csrf', $cookie, 'Too late!');
        $this->addSubmit('login', 'Login');
        return $this;
    }
}


$inputs = new VarsAdapter();
$inputs->loadEntries(VarsAdapter::SOURCE_POST);
$login = new LoginForm('login');
$login->set(new CookieAdapter());
$login->setInputs($inputs);

if ($login->process('login')) {
    $login->getValues();
    // process things from form
}

$login->render();
