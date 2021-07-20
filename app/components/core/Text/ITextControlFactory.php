<?php

namespace App\Components;

/**
 * Interface ITextControlFactory
 *
 * @author  Tomáš Surovčík <tomas.surovcik@q2.cz>
 * @package QetteWeb
 */
interface ITextControlFactory
{
    /** @return TextControl */
    public function create();
}