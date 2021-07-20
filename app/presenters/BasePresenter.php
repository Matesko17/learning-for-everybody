<?php

namespace App\Presenters;

use Nette\Bridges\ApplicationLatte\Template;

/**
 * Base presenter for all application presenters.
 * @property-read Template|\stdClass $template
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb
 */
abstract class BasePresenter extends AbstractQwPresenter
{
   
}
