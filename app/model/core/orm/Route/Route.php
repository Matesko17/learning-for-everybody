<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * Route entity
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb
 * 
 * @ORM\Entity(repositoryClass="App\Model\Repositories\RouteRepository")
 * @ORM\Table(name="`route`", uniqueConstraints={@ORM\UniqueConstraint(name="presenter_action_idx", columns={"presenter", "action"})})
 */
class Route
{
    use Identifier;

    /**
     * @ORM\Column(type="string")
     */
    protected $presenter;

    /**
     * @ORM\Column(type="string")
     */
    protected $action;

    // SETTERS

    public function setPresenter($presenter)
    {
        $this->presenter = $presenter;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    // GETTERS

    public function getPresenter()
    {
        return $this->presenter;
    }

    public function getAction()
    {
        return $this->action;
    }
    
    public function toString()
    {
        return ":".$this->presenter.":".$this->action;
    }
}
