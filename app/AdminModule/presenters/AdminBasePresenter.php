<?php

namespace App\AdminModule\Presenters;

use Nette\Application\UI\Presenter;

use App\Presenters\BasePresenter;
use App\AdminModule\Services\Authenticator;

/**
 * Class AdminBasePresenter.
 * 
 * @author Kamil Walig <kamil.walig@q2.cz>
 * @author Jan Hermann <jan.hermann@q2.cz>
 * @author Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb
 */
abstract class AdminBasePresenter extends BasePresenter
// abstract class AdminBasePresenter extends BasePresenter
{
    /** @var Authenticator @inject */
    public $authenticator;

    public function startup()
    {
        parent::startup();
    }

    public function checkRequirements($element)
    {
        parent::checkRequirements($element);
        $this->getUser()->getStorage()->setNamespace('admin');
        $this->getUser()->setAuthenticator($this->authenticator);
    }
}
