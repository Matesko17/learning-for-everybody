<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * RouteAlias entity
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb
 * 
 * @ORM\Entity(repositoryClass="App\Model\Repositories\RouteAliasRepository")
 * @ORM\Table(name="`route_alias`", uniqueConstraints={@ORM\UniqueConstraint(name="language_slug_idx", columns={"language", "slug"})})
 */
class RouteAlias
{
    use Identifier;

    /**
     * Many RouteAliases have One Route.
     * @ORM\ManyToOne(targetEntity="Route", inversedBy="routeAliases")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $route;

    /**
     * @ORM\Column(type="string")
     */
    protected $language;

    /**
     * @ORM\Column(type="string")
     */
    protected $slug;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $item;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $parameters;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $deleted;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $added;

    // SETTERS

    public function setRoute($route)
    {
        $this->route = $route;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function setItem($item)
    {
        $this->item = $item;
    }

    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    public function setAdded($added)
    {
        $this->added = $added;
    }

    // GETTERS

    public function getRoute()
    {
        return $this->route;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getDeleted()
    {
        return $this->deleted;
    }

    public function getAdded()
    {
        return $this->added;
    }
}
