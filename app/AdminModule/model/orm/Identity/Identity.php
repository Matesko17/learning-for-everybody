<?php

namespace App\AdminModule\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * Identity entity.
 * 
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb
 * 
 * @ORM\Entity(repositoryClass="App\AdminModule\Model\Repositories\IdentityRepository")
 * @ORM\Table(name="`identity`")
 */
class Identity
{
    // use Identifier;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="login", type="string")
     */
    protected $login;

    /**
     * @ORM\Column(name="passwd", type="string")
     */
    protected $passwd;

    /**
     * @ORM\Column(name="role", type="string", nullable=true)
     */
    protected $role;

    /**
     * @ORM\Column(name="username", type="string")
     */
    protected $username;

    /**
     * @ORM\Column(name="email", type="string")
     */
    protected $email;

    /**
     * @ORM\Column(name="active", type="boolean")
     */
    protected $active;

    /**
     * @ORM\Column(name="added", type="datetime")
     */
    protected $added;

    // SETTERS

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    // GETTERS

    final public function getId()
    {
        return $this->id;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->passwd;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function isActive()
    {
        return $this->active;
    }

    public function getAdded()
    {
        return $this->added;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}
