<?php

namespace App\Components;

/**
 * Interface ILanguageSwitchControlFactory
 *
 * @author  Tomáš Surovčík <tomas.surovcik@q2.cz>
 * @package QetteWeb
 */
interface ILanguageSwitchControlFactory
{
    /** @return LanguageSwitchControl */
    public function create();
}