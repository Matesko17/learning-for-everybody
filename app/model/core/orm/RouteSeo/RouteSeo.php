<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * RouteSeo entity
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb
 * 
 * @ORM\Entity(repositoryClass="App\Model\Repositories\RouteSeoRepository")
 * @ORM\Table(name="`route_seo`", uniqueConstraints={@ORM\UniqueConstraint(name="route_language_item_idx", columns={"route_id", "language", "item"})})
 */
class RouteSeo
{
    use Identifier;

    /**
     * Many RouteSeos have One Route.
     * @ORM\ManyToOne(targetEntity="Route", inversedBy="routeSeos")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $route;

    /**
     * @ORM\Column(type="string")
     */
    protected $language;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $item;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $keywords;

    // SETTERS

    public function setRoute($route)
    {
        $this->route = $route;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function setItem($item)
    {
        $this->item = $item;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
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

    public function getItem()
    {
        return $this->item;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }
}
