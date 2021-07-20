<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable as KnpTranslatable;
use Zenify\DoctrineBehaviors\Entities\Attributes\Translatable as ZenifyTranslatable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * Text entity
 * 
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb
 * 
 * @ORM\Entity(repositoryClass="App\Model\Repositories\TextRepository")
 * @ORM\Table(name="`text`", uniqueConstraints={@ORM\UniqueConstraint(name="text_ident_idx", columns={"ident"})})
 */
class Text
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
