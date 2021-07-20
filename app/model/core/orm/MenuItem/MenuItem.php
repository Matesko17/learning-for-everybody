<?php

namespace App\Model\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * MenuItem entity
 *
 * @author  Kamil Walig <kamil.walig@q2.cz>
 * @author  Jakub Markus <jakub.markus@q2.cz>
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @author  Tomáš Surovčík <tomas.surovcik@q2.cz>
 * @author  Jan Hermann <jan.hermann@q2.cz>
 * @package Qetteweb
 *
 * @ORM\Entity(repositoryClass="App\Model\Repositories\MenuItemRepository")
 * @ORM\Table(name="`menu_item`", indexes={@ORM\Index(name="route_alias_id", columns={"route_alias_id"}), @ORM\Index(name="menu_id", columns={"menu_id"}), @ORM\Index(name="parent_id", columns={"parent_id"})})
 */
class MenuItem
{
    use Identifier, Timestampable;
    
    /**
     * Many MenuItems have One Menu.
     * @var Menu
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="menuItems")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="menu_id", referencedColumnName="id", nullable=false)
     * })
     */
    protected $menu;
    
    /**
     * Many MenuItems have One MenuItem (as a parent).
     * @var MenuItem|null
     * @ORM\ManyToOne(targetEntity="MenuItem", inversedBy="children")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    protected $parent;
    
    /**
     * One MenuItem has Many MenuItems.
     * @var Collection|ArrayCollection
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="parent")
     * @ORM\OrderBy({"order" = "ASC"})
     */
    protected $children;
    
    /**
     * @var bool|null
     * @ORM\Column(name="blank", type="boolean", nullable=true)
     */
    protected $blank;
    
    /**
     * Many MenuItems have One RouteAlias.
     * @var RouteAlias|null
     * @ORM\ManyToOne(targetEntity="RouteAlias", inversedBy="menuItems")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="route_alias_id", referencedColumnName="id")
     * })
     */
    protected $routeAlias;
    
    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    protected $title;
    
    /**
     * @var string|null
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    protected $link;
    
    /**
     * @var string|null
     * @ORM\Column(name="anchor", type="string", length=255, nullable=true)
     */
    protected $anchor;
    
    /**
     * @var bool
     * @ORM\Column(name="dropdown", type="boolean", nullable=false, options={"default"="0"})
     */
    protected $dropdown;
    
    /**
     * @var bool
     * @ORM\Column(name="show", type="boolean", nullable=false, options={"default":"0"})
     */
    protected $show;
    
    /**
     * @var int
     * @ORM\Column(name="order", type="integer", nullable=false, options={"default"="1"})
     */
    protected $order;
    
    /**
     * @var int|null
     * @ORM\Column(name="tree_access_rights", type="integer", nullable=true)
     */
    protected $treeAccessRights;
    
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }
    
    /**
     * @return Menu
     */
    public function getMenu(): Menu
    {
        return $this->menu;
    }
    
    /**
     * @return MenuItem|null
     */
    public function getParent(): ?MenuItem
    {
        return $this->parent;
    }
    
    /**
     * @param bool|null
     * @return Collection
     */
    public function getChildren(?bool $show = true): Collection
    {
        if(is_bool($show)) {
            $criteria = Criteria::create()
                ->where(Criteria::expr()->eq("show", $show))
                ->orderBy(["order" => "ASC"]);
            
            return $this->children->matching($criteria);
        } else {
            return $this->children;
        }
    }
    
    /**
     * @return bool|null
     */
    public function getBlank(): ?bool
    {
        return $this->blank;
    }
    
    /**
     * @return RouteAlias|null
     */
    public function getRouteAlias(): ?RouteAlias
    {
        return $this->routeAlias;
    }
    
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    
    /**
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }
    
    /**
     * @return string|null
     */
    public function getAnchor(): ?string
    {
        return $this->anchor;
    }
    
    /**
     * @return bool
     */
    public function isDropdown(): bool
    {
        return $this->dropdown;
    }
    
    /**
     * @return bool
     */
    public function isShow(): bool
    {
        return $this->show;
    }
    
    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }
    
    /**
     * @return int|null
     */
    public function getTreeAccessRights(): ?int
    {
        return $this->treeAccessRights;
    }
    
    /**
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
