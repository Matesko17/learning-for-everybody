<?php

namespace App\Services;

use Nette\Application\UI\Presenter;

/**
 * Class InternalRouterPresenter.
 * Internal class for route inserting.
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
class InternalRouterPresenter extends Presenter
{
    private $name;
    
    private $action;
    
    /**
     * InternalRouterPresenter constructor.
     * @param string $name
     * @param string $action
     * @param array $parameters
     */
    public function __construct(string $name = "Homepage", string $action = "default", $parameters = [])
    {
        $this->name = $name; // name insert
        $this->action = $action; // action insert
        $this->params = $parameters; // parameter insert
        $this->params['action'] = $action; // action insert (into parameter)
    }
    
    /**
     * Name getter.
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Action getter.
     * @param bool $fullyQualified
     * @return mixed
     */
    public function getAction($fullyQualified = false)
    {
        return $fullyQualified ? ':'.$this->getName().':'.$this->action : $this->action;
    }
}
