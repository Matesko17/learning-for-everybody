<?php

namespace App;

use App\Services\DatabaseRouter;
use App\Services\SiteSettingService;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\DI\Container;

/**
 * Class RouterFactory
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
class RouterFactory
{
    const SECURED_ROUTER = 0b0010;
    
    /** @var Container */
    private $context;
    
    /** @var string */
    private $lang;
    
    /** @var bool */
    private $https;
    
    /** @var SiteSettingService */
    private $siteSettingService;
    
    /**
     * Default constructor.
     * @param Container $container
     * @param SiteSettingService $siteSettingService
     */
    public function __construct(Container $container, SiteSettingService $siteSettingService)
    {
        $this->context = $container; // saving context (DI)
        $this->siteSettingService = $siteSettingService;
        
        $this->lang = $this->siteSettingService->get('mainLang'); // getting main language
        $this->https = $container->parameters['https'];
    }
    
    /**
     * Creating router
     * @return RouteList
     * @throws \Exception
     */
    public function createRouter()
    {
        $router = new RouteList();
        
        $allowLang = $this->siteSettingService->get('allowLang');
        $langs = implode('|', array_keys($allowLang));
        
        // fix for running on different ports in development than 80 (for example in Docker)
        if($this->https == false and file_exists(__DIR__.'/../.development')) {
            $path = '';
        } else {
            $path = 'http'.($this->https ? 's' : '').'://'.'%host%/%basePath%/';
        }
        
        // admin module
        $router[] = new Route(
            $path.'[<locale ('.$langs.')>/]qred/<presenter>/<action>[/<id>]',
            ['module' => 'Admin', 'presenter' => 'Homepage', 'action' => 'default', 'locale' => $this->lang]
        );
        
        // database router
        if($this->context->parameters['router']['database']) {
            $router[] = new DatabaseRouter(
                $this->context, null,
                ['presenter' => 'Homepage', 'action' => 'default', 'locale' => $this->lang],
                ($this->https ? self::SECURED_ROUTER : 0)
            );
        }
        
        // application module
        $router[] = new Route(
            $path.'[<locale ('.$langs.')>/]<presenter>/<action>[/<id>]',
            ['presenter' => 'Homepage', 'action' => 'default', 'locale' => $this->lang]
        );
        
        return $router;
    }
}
