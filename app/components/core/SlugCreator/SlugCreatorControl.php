<?php

namespace App\Components;

use Nette\Application\UI\Control;
use App\Services\DatabaseRouterModel;

/**
 * Class SlugCreatorControl.
 * Component ensuring slug transfer from latte to router tables.
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
class SlugCreatorControl extends Control
{
    /** @var DatabaseRouterModel */
    private $databaseRouterModel = null;


    /**
     * SlugCreatorControl constructor.
     * @param DatabaseRouterModel $databaseRouterModel
     */
    public function __construct(DatabaseRouterModel $databaseRouterModel)
    {
        $this->databaseRouterModel = $databaseRouterModel;
    }


    /**
     * Component render method.
     * @param $slug
     */
    public function render($slug)
    {
        if ($this->databaseRouterModel && $slug) {
            $this->databaseRouterModel->insertSlug($this->presenter, $slug);
        }
    }
}
