<?php

namespace App\AdminModule\Model\Facades;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Nette\Utils\Random;
use Nette\Utils\ArrayHash;
use Nette\Security\Passwords;

use App\AdminModule\Model\Entities\Identity;
use App\AdminModule\Model\Repositories\IdentityRepository;

/**
 * Class IdentityFacade.
 * 
 * @author  Martin Skyba
 * @package Qetteweb\AdminModule
 */
class IdentityFacade
{

    /** @var IdentityRepository */
    protected $identityRepository;

    /** @var EntityManager  */
    protected $entityManager;

    /**
     * IdentityFacade constructor.
     * @param $entityClassName
     * @param EntityManager $entityManager
     * @throws EntityNotFoundException
     */
    public function __construct(EntityManager $entityManager) {
    	$this->entityManager = $entityManager;
    	$this->identityRepository = $this->entityManager->getRepository(Identity::class);
    }

    public function getAllIdentities()
    {
    	return $this->identityRepository->findAll();
    }

    public function getIdentityById($id)
    {
    	return $this->identityRepository->findOneById($id);
    }

    public function getIdentityByLogin(string $login)
    {
    	return $this->identityRepository->findOneBy(['login' => $login]);
    }

    /**
     * @param string $email
     * @param string $password
     * @param ArrayHash $data
     * @return mixed|null|object
     * @throws DuplicateEmailException
     */
    public function register(string $email, string $password, ArrayHash $data)
    {
        // check email existence, throw exception in case it is duplicate
        $identity = $this->getIdentityByEmail($email);

        if ($identity) {
            throw new DuplicateEmailException();
        }

        // override important data and set it right
        $identity = new $this->entityClassName;

        // save data
        $identity->setNickname($data['nickname']);
        $identity->setFirstname($data['firstname']);
        $identity->setLastname($data['lastname']);
        $identity->setEmail($email);
        $identity->setPassword(Passwords::hash($password));
        $identity->setAuthToken(sha1($identity->getEmail() . Random::generate(15)));
        $authTokenValidityDate = new \DateTime();
        $identity->setAuthTokenValidity($authTokenValidityDate->modify('+1 day'));

        // explicit allow when register
        $identity->setActive(false);

        $this->save($identity);

        return $identity;
    }

    /**
     * @param Identity $identity
     * @return Identity
     */
    public function activate(Identity $identity)
    {
        $identity->setAuthToken("");
        $identity->setActive(true);

        $this->save($identity);

        return $identity;
    }

    public function changePassword(Identity $identity, string $password)
    {
        $identity->setPassword(Passwords::hash($password));
        $identity->setAuthToken("");
        $identity->setActive(true);

        $this->save($identity);

        return $identity;
    }

    public function edit(Identity $identity, ArrayHash $data)
    {
        foreach ($data as $key => $value) {
            $setMethodName = 'set' . ucfirst($key);
            $identity->$setMethodName($value);
        }
        
        $this->save($identity);

        return $identity;
    }
    

    // public function authenticate($credentials)
    // {
    // 	return $this->authenticator->authenticate($credentials);
    // }

    // public function createHash($password)
    // {
    // 	return $this->authenticator->createHash($password);
    // }
    
    // public function verify($password, $identityPasswordHash)
    // {
    // 	return $this->authenticator->createHash($password, $identityPasswordHash);
    // }
    
    /**
     * @param string $email
     * @return Identity|null|object
     * @throws EntityNotFoundException
     */
    public function resetPassword(string $email)
    {
        // try to find identity
        $identity = $this->identityRepository->findOneBy(['email' => $email]);

        if ($identity) {
            // generate and persist authToken 
            $identity->setAuthToken(sha1($identity->getEmail() . Random::generate(15)));
            $identity->setAuthTokenValidity((new \DateTime())->modify('+30 minutes'));

            $this->save($identity);
            
            return $identity;
        } else {
            throw new EntityNotFoundException();
        }
    }

    /**
     * @param string $authToken
     * @return bool|mixed|null|object
     */
    public function validateAuthToken(string $authToken)
    {
        // try to find identity - check validity of the date
        $identity = $this->identityRepository->findOneBy(['authToken' => $authToken]);

        if ($identity && !$identity->isBlocked() && $identity->getAuthTokenValidity() > new \DateTime()) {
            return $identity;
        } else {
            return false;
        }
    }

    public function save($identity)
    {
        if ($identity->getId()) {
            $this->entityManager->merge($identity);
        } else {
            $this->entityManager->persist($identity);
        }
        $this->entityManager->flush();
    }
}

class DuplicateEmailException extends \Exception {}
class ClassNotFoundException extends \Exception {}
