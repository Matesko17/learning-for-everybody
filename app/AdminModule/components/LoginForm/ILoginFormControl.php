<?php

namespace App\AdminModule\Components;

use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

/**
 * Interface ILoginFormControl.
 * 
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb\AdminModule
 */
interface ILoginFormControl
{
    public function formSuccess(Form $form, ArrayHash $values);
    public function handleOut();
}
