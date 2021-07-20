<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable as KnpTranslatable;
use Zenify\DoctrineBehaviors\Entities\Attributes\Translatable as ZenifyTranslatable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * Page entity
 * 
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb
 * 
 * @ORM\Entity(repositoryClass="App\Model\Repositories\PageRepository")
 * @ORM\Table(name="`page`", uniqueConstraints={@ORM\UniqueConstraint(name="page_ident_idx", columns={"ident"})})
 */
class Page
{
    use Identifier;

    use KnpTranslatable;
    // returns translated property for $article->getTitle() or $article->title
    use ZenifyTranslatable;

    use Timestampable;

    /**
     * @ORM\Column(type="string")
     */
    protected $ident;

    // SETTERS

    public function setIdent($ident)
    {
        $this->ident = $ident;
    }

    // GETTERS

    public function getIdent()
    {
        return $this->ident;
    }
}
