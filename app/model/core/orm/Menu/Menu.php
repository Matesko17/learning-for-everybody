<?php

namespace App\Model\Entities;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * Menu entity
 *
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @author  Jan Hermann <jan.hermann@q2.cz>
 * @package Qetteweb
 *
 * @ORM\Entity(repositoryClass="App\Model\Repositories\MenuRepository")
 * @ORM\Table(name="`menu`", uniqueConstraints={@ORM\UniqueConstraint(name="ident_locale", columns={"ident", "locale"})})
 */
class Menu
{
    use Identifier;
    
    /**
     * One Menu has Many MenuItems.
     * @var Collection
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="menu")
     */
    protected $menuItems;
    
    /**
     * @var string
     * @ORM\Column(name="ident", type="string", length=255, nullable=false)
     */
    protected $ident;
    
    /**
     * @var string
     * @ORM\Column(name="locale", type="string", length=255, nullable=false)
     */
    protected $locale;
    
    /**
     * @return Collection
     */
    public function getMenuItems(): Collection
    {
        return $this->menuItems;
    }
    
    /**
     * @return string
     */
    public function getIdent(): string
    {
        return $this->ident;
    }
    
    /**
     * @param string $ident
     * @return Menu
     */
    public function setIdent(string $ident): Menu
    {
        $this->ident = $ident;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }
}
