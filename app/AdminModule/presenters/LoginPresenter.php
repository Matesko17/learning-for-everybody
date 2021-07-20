<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\ILoginFormControlFactory;

/**
 * Class LoginPresenter.
 * 
 * @author Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb
 */
class LoginPresenter extends AdminBasePresenter
{
    /** @var ILoginFormControlFactory @inject */
    public $loginFormControlFactory;

    public function renderDefault()
    {

    }

    protected function createComponentLoginFormControl() {
        $component = $this->loginFormControlFactory->create();
        return $component;
    }
}
