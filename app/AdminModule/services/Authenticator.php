<?php

namespace App\AdminModule\Services;

use Nette\Security\Passwords;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\AuthenticationException;
use Kdyby\Translation\Translator;
use App\AdminModule\Model\Facades\IdentityFacade;

/**
 * Class Authenticator.
 * 
 * @author  Martin Skyba
 * @package Qetteweb\AdminModule
 */
class Authenticator implements IAuthenticator
{
    use \Nette\SmartObject;
    
    /** @var Translator  */
    private $translator;

    /** @var IdentityFacade @inject */
    private $identityFacade;

    public function __construct(Translator $translator, IdentityFacade $identityFacade)
    {
        $this->translator = $translator;
        $this->identityFacade = $identityFacade;
    }


    /**
     * User authentication.
     * @param array $credentials
     * @return Identity
     * @throws AuthenticationException
     */
    public function authenticate(array $credentials)
    {
        list($login, $password) = $credentials;
        $identity = $this->identityFacade->getIdentityByLogin($login);

        $cursor = $identity && $this->verify($password, $identity->getPassword()) ? $identity : false;
        if ($cursor) {
            if ($cursor->isActive()) {
                $identityData = $cursor->toArray();
                return new \Nette\Security\Identity($cursor->getId(), $cursor->getRole(), $identityData);
            } else {
                throw new \Nette\Security\AuthenticationException($this->translator->translate('q2IdentityExt.authenticator.authenticate.notApproved'), self::NOT_APPROVED);
            }
        } else {
            throw new \Nette\Security\AuthenticationException($this->translator->translate('q2IdentityExt.authenticator.authenticate.invalidCredential'), self::INVALID_CREDENTIAL);
        }
    }


    public function createHash($password)
    {
        return Passwords::hash($password, ['cost' => 12]);
    }


    public function verify($password, $identityPasswordHash)
    {
        return \Nette\Security\Passwords::verify($password, $identityPasswordHash);
    }
    
}
