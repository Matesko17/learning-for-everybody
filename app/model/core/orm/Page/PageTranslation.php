<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * PageTranslation entity
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb
 * 
 * @ORM\Entity
 * @ORM\Table(name="`page_translation`")
 */
class PageTranslation
{
    use Translation;
    use Timestampable;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $text;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    protected $visible;

    // GETTERS

    public function getTitle()
    {
        return $this->title;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getVisible()
    {
        return $this->visible;
    }
}
