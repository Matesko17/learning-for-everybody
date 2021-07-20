<?php

namespace App\AdminModule\Model\Facades;

use Nette\Utils\ArrayHash;
use App\AdminModule\Entity\IIdentity;

/**
 * Interface IIdentityFacade.
 * 
 * @author  Martin Skyba
 * @package Qetteweb\AdminModule
 */
interface IIdentityFacade
{
    public function getAllIdentities();
    public function getIdentityByEmail(string $email);
    public function register(string $email, string $password, ArrayHash $data);
    public function resetPassword(string $email);
    public function validateAuthToken(string $authToken);
    public function changePassword(IIdentity $identity, string $password);
}