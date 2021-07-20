<?php

/**
 * Interface IInvalidateCache
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
interface IInvalidateCache
{

    /**
     * Cache ivalidate hook.
     */
    function hookInvalidateCache();
}
