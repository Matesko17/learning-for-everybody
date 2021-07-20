<?php

namespace App\Components;

/**
 * Interface IMenuControlFactory
 *
 * @author  Tomáš Surovčík <tomas.surovcik@q2.cz>
 * @package QetteWeb
 */
interface IMenuControlFactory
{
    /** @return MenuControl */
    public function create();
}