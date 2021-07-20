<?php

namespace App\AdminModule\Components;

use Kdyby\Translation\Translator;
use Nette\Application\UI\Form;

/**
 * Class LoginForm.
 * 
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb\AdminModule
 */
class LoginForm implements ILoginForm
{
    /** @var Translator  */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function create()
    {
        $form = new Form();

        $form->setTranslator($this->translator);
        $form->addText('login', 'admin.loginFormControl.loginForm.login.name')
            ->setAttribute('placeholder', 'admin.loginFormControl.loginForm.login.name')
            ->setRequired('admin.loginFormControl.loginForm.login.required');
        $form->addPassword('password', 'admin.loginFormControl.loginForm.password.name')
            ->setAttribute('placeholder', 'admin.loginFormControl.loginForm.password.placeholder')
            ->setAttribute('autocomplete', 'off')
            ->setRequired('admin.loginFormControl.loginForm.password.required');
        $form->addCheckbox('rememberme', 'admin.loginFormControl.loginForm.rememberme.name');

        return $form;
    }
}
