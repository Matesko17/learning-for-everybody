<?php

namespace App\AdminModule\Presenters;

/**
 * Class SecurityBasePresenter.
 * 
 * Security base presenter for all security application presenters.
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb
 */
abstract class SecurityBasePresenter extends AdminBasePresenter
{
    public function startup()
    {
        parent::startup();

        $user = $this->getUser();

        // if not logged in, redirect to login page
        if (!$user->isLoggedIn()) {
            $user->logout(true);
            $this->redirect('Login:');
        }
    }
}
