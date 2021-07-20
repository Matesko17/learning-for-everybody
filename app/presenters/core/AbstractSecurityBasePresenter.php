<?php

namespace App\Presenters;

use Nette\Application\AbortException;

/**
 * Class AbstractSecurityBasePresenter
 * Security base presenter for all security application presenters.
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
abstract class AbstractSecurityBasePresenter extends AbstractQwPresenter
{
    /**
     * starter
     * @throws AbortException
     */
    public function startup()
    {
        parent::startup();
        
        $user = $this->getUser();
        // pokud neni prihlaseno tak vraci na home
        if(!$user->isLoggedIn()) {
            $user->logout(true);
            $this->redirect('Homepage:');
        }
    }
}
