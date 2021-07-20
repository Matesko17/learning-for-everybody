<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * AdminLog entity
 * 
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb
 * 
 * @ORM\Entity(repositoryClass="App\Model\Repositories\AdminLogRepository")
 * @ORM\Table(name="`admin_log`")
 */
class AdminLog
{
    use Identifier;

    /**
     * @ORM\Column(type="string")
     */
    protected $tableName;

    /**
     * @ORM\Column(type="string")
     */
    protected $primaryKeyValue;

    /**
     * @ORM\Column(type="string")
     */
    protected $action;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $identityId;

    /**
     * @ORM\Column(type="string")
     */
    protected $username;

    /**
     * @ORM\Column(type="string")
     */
    protected $ip;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $timestamp;

    // GETTERS

    public function getLogin()
    {
        return $this->tableName;
    }

    public function getPasswd()
    {
        return $this->primaryKeyValue;
    }

    public function getRole()
    {
        return $this->action;
    }

    public function getUsername()
    {
        return $this->identityId;
    }

    public function getEmail()
    {
        return $this->username;
    }

    public function getActive()
    {
        return $this->ip;
    }

    public function getAdded()
    {
        return $this->timestamp;
    }
}
