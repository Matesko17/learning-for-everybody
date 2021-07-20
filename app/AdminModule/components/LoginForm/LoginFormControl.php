<?php

namespace App\AdminModule\Components;

use Kdyby\Translation\Translator;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;
use Nette\Security\AuthenticationException;
use Nette\Application\UI\Control;

use App\AdminModule\Model\Facades\IdentityFacade;
use App\AdminModule\Services\Authenticator;


interface ILoginFormControlFactory
{
    /**
     * @return LoginFormControl
     */
    public function create();
}

/**
 * Class LoginFormControl.
 * 
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb\AdminModule
 */
class LoginFormControl extends Control implements ILoginFormControl
{
    /** @var Translator  */
    private $translator;

    /** @var IdentityFacade */
    private $identityFacade;

    /** @var ILoginForm */
    private $loginForm;

    /** @var string  */
    private $pathTemplate;

    /** @var Authenticator */
    private $authenticator;
    
    /**
     * @param string $pathTemplate
     */
    public function setPathTemplate(string $pathTemplate) {
        $this->pathTemplate = $pathTemplate;
    }
    
    /**
     * 
     * @param Translator $translator
     */
    public function __construct(
        Translator $translator,
        IdentityFacade $identityFacade,
        ILoginForm $loginForm,
        Authenticator $authenticator
    ) {
        parent::__construct();
        $this->translator = $translator;
        $this->identityFacade = $identityFacade;
        $this->loginForm = $loginForm;
        $this->authenticator = $authenticator;

        $this->pathTemplate = __DIR__ . '/LoginFormControl.latte';
    }


    /**
     * Render method.
     */
    public function render()
    {
        $template = $this->getTemplate();
        $template->addFilter(null, 'LatteFilters::common');
        $template->setTranslator($this->translator);
        $template->setFile($this->pathTemplate);
        $template->render();
    }


    /**
     * Form component creator method
     * @param  [type] $name [description]
     * @return Form
     */
    protected function createComponentForm($name)
    {
        $form = $this->loginForm->create();

        $form->addSubmit('send', 'admin.loginFormControl.createComponentForm.send');
        $form->onSuccess[] = [$this, 'formSuccess'];
        
        return $form;
    }


    /**
     * Success callback for login form method
     * @param Form $form
     * @param ArrayHash $values
     */
    public function formSuccess(Form $form, ArrayHash $values)
    {
        $presenter = $this->getPresenter();
        try {
            $presenter->user->login($values->login, $values->password);
            $presenter->user->setExpiration($values['rememberme'] ? '2 days' : '1 hour', TRUE);
            $presenter->redirect('Homepage:');
        } catch (AuthenticationException $e) {
            $presenter->flashMessage($e->getMessage(), 'error');
        }
    }


    /**
     * Logout signal method
     */
    public function handleOut()
    {
        $presenter = $this->getPresenter();
        $presenter->user->logout(true);
        $presenter->flashMessage($this->translator->translate('admin.loginFormControl.signout.message.success'), 'info');
        $presenter->redirect('this');
    }
}
