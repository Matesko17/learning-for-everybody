<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * Translation entity
 *
 * @author  Kamil Walig <kamil.walig@q2.cz>
 * @author  Jakub Markus <jakub.markus@q2.cz>
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb
 * 
 * @ORM\Entity(repositoryClass="App\Model\Repositories\TranslationRepository")
 * @ORM\Table(name="`translation`", uniqueConstraints={
 *     @ORM\UniqueConstraint(
 *          name="namespace_section_key_lang_idx",
 *          columns={"namespace", "section", "key", "lang"}
 *     )}
 * )
 */
class Translation
{
    use Identifier;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $namespace;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $section;

    /**
     * @ORM\Column(name="`key`", type="string", nullable=false)
     */
    protected $key;

    /**
     * @ORM\Column(name="`default`", type="string", nullable=false)
     */
    protected $default;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $translate;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $lang;

    /**
     * @ORM\Column(type="smallint", options={"default" : 0})
     */
    protected $updated;

    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param mixed $namespace
     * @return Translation
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param mixed $section
     * @return Translation
     */
    public function setSection($section)
    {
        $this->section = $section;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     * @return Translation
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $default
     * @return Translation
     */
    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTranslate()
    {
        return $this->translate;
    }

    /**
     * @param mixed $translate
     * @return Translation
     */
    public function setTranslate($translate)
    {
        $this->translate = $translate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param mixed $lang
     * @return Translation
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     * @return Translation
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

}
