<?php

namespace App\Services;

use Nette\Application\IRouter;
use Nette\Application\Request;
use Nette\Http\IRequest;
use Nette\DI\Container;
use Nette\Http\Url;

/**
 * Class DatabaseRouter.
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
class DatabaseRouter implements IRouter
{
    private $metadata, $flags, $mask, $saveArray;
    private $configure = null;
    private $mainLang, $languages = [];

    /** @var AbstractDatabaseRouterModel */
    private $routerModel = null;

    // visual paginator separator
    private $vpSeparator = '_'; // default: '_'
    
    // visual paginator selector
    private $vpVariable = 'visualPaginator-page';

    /**
     * DatabaseRouter constructor.
     * @param Container $context
     * @param null $saveArray
     * @param array $metadata
     * @param int $flags
     * @throws \Exception
     */
    public function __construct(Container $context, $saveArray = null, $metadata = [], $flags = 0)
    {
        // internal slug structure and parameter saving
        $this->mask = ['<locale>', '/', '<slug>', $this->vpSeparator, '<vp>']; // mask for url address composition
        $this->saveArray = $saveArray; // extra variables, which should be saved

        $this->metadata = $metadata;
        $this->flags = $flags;

        if (isset($context->parameters['router'])) {
            // loading configuration from neon
            $this->configure = $context->parameters['router'];
        } else {
            // default setting (for case of missing customization)
            $this->configure = [
                'languageDomainSwitch' => false,
                'languageDomainAlias' => [],
            ];
        }

        // model existence check
        if ($context->findByType(AbstractDatabaseRouterModel::class)) {
            $this->routerModel = $context->getByType(AbstractDatabaseRouterModel::class); // loading model instance
            $this->routerModel->setDatabaseRouter($this);

            $languageService = $this->routerModel->getLanguageService();
            $this->mainLang = $languageService->getMainLang();
            $this->languages = array_flip($languageService->getAvailableLanguages());
            $this->languagesCode = $languageService->getAvailableLanguages();
        } else {
            throw new \Exception("Service typu " . AbstractDatabaseRouterModel::class . " neni definovana!!");
        }
    }


    /**
     * Filtering parameters saving to database according to $saveArray array.
     * @param $parameters
     * @return array
     */
    public function filterParameters($parameters)
    {
        if ($this->saveArray) {
            return array_intersect_key($parameters, array_flip($this->saveArray));
        }
        return null;
    }


    // TODO: Write better comment for filter params + solve https

    public function setSecured($bool)
    {
    }


    /**
     * Url address composition by mask.
     * @param $slug
     * @param $params
     * @return string
     */
    private function buildUrl($slug, $params)
    {
        $params['slug'] = $slug;
        // removing language in case it is same as main language
        if (isset($params['locale']) && $params['locale'] == $this->mainLang) {
            $params['locale'] = '';
        }

        // host - removing language from address
        if ($this->configure['languageDomainSwitch']) {
            unset($params['locale']);
        }

        // transformation visual paginator variable into internal variable
        if (isset($params[$this->vpVariable])) {
            $params['vp'] = $params[$this->vpVariable];
        }

        // adding parameters array into mask
        $adrArray = array_map(function ($r) use ($params) {
            if (preg_match('/\<([a-z]+)\>/', $r, $m) && $m && isset($m[1])) {
                return (isset($params[$m[1]]) ? $params[$m[1]] : null);
            } else {
                return $r;
            }
        }, $this->mask);

        return trim(implode($adrArray), '/_'); // composing array and removing slashes and underscores
    }
    
    /**
     * Maps HTTP request to a Request object.
     * @param IRequest $httpRequest
     * @return Request|null
     * @throws \Throwable
     */
    public function match(IRequest $httpRequest)
    {
        $slug = $httpRequest->getUrl()->getPathInfo();
        $presenter = null;
        $parameters = [];

        // insterting default default values in case of empty address
        if (!$slug && $this->metadata) {
            $parameters = $this->metadata; // inserting parameters from metadata
            // if presenter is defined, pass it on and unset index
            if (isset($parameters['presenter'])) {
                $presenter = $parameters['presenter'];
                unset($parameters['presenter']);
            }
        }

        $lang = null;
        // setting language directly from url address for right system handling (db + translates)
        if (preg_match('/((?<locale>[a-z]{2})\/)?/', $slug, $m) && isset($m['locale'])) {
            $lang = $m['locale'];
            $slug = trim(substr($slug, strlen($m['locale']), strlen($slug)), '/_'); // removing language from from slug
        }

        // host detection - distinguish according to domain
        if ($this->configure['languageDomainSwitch']) {
            $host = $httpRequest->url->host; // loading host url for language election
            if (isset($this->configure['languageDomainAlias'][$host])) {
                // in case of domain alias exists
                $parameters['locale'] = $this->configure['languageDomainAlias'][$host];
            } else {
                // in case of domain alias doesn't exist
                $parameters['locale'] = $this->mainLang;
            }
        }

        // setting language for language service
        if (isset($parameters['locale'])) {
            $lang = $parameters['locale'];
        }

        $this->routerModel->getLanguageService()->setLang($lang); // loading language service

        // slug edit because of visual paginator
        if (preg_match('/(.+\\' . $this->vpSeparator . '(?<vp>[0-9]+))?/', $slug, $m) && isset($m['vp'])) {
            $parameters[$this->vpVariable] = $m['vp'];
            $slug = trim(substr($slug, 0, strlen($slug) - strlen($m['vp'])), '/_'); // removing paginator from slug
        }

        // acceptation of address, which contains redundant slash at the end and removing it
        $slug = rtrim($slug, '/_');


        // if slug is defined
        if ($slug) {
            $c = $this->routerModel->getBySlug($slug); // loading route according to slug
            if ($c) {
                // loading presenter from database
                $presenter = $c->presenter;

                // loading extra parameters from database
                if ($c->parameters) {
                    $parameters += unserialize($c->parameters);
                }

                // loading language from database
                $parameters['locale'] = $c->language;

                // loading action from database
                $parameters['action'] = $c->action;

                // loading id from database
                if ($c->item) {
                    $parameters['id'] = $c->item;
                }
            } else {
                return null;
            }
        }

        // adding query parameter
        $parameters += $httpRequest->getQuery();

        // protection against empty presenter
        if (!$presenter) {
            return null;
        }

        return new Request(
            $presenter,
            $httpRequest->getMethod(),
            $parameters,
            $httpRequest->getPost(),
            $httpRequest->getFiles(),
            [Request::SECURED => $httpRequest->isSecured()]
        );
    }
    
    /**
     * Constructs absolute URL from Request object.
     * @param Request $appRequest
     * @param Url $refUrl
     * @return null|string
     * @throws \Throwable
     */
    public function constructUrl(Request $appRequest, Url $refUrl)
    {
        if ($this->flags & self::ONE_WAY) {
            return null;
        }

        $c = $this->routerModel->getSlugByParams($appRequest->presenterName, $appRequest->parameters); // loading slug according to presenter and parameter
        
        $loadParams = $appRequest->parameters;
        // removing already saved values
        unset($loadParams['locale'], $loadParams['action'], $loadParams['id'], $loadParams['vp'], $loadParams[$this->vpVariable]);

        // removing extra parameters
        if ($this->saveArray) {
            foreach ($this->saveArray as $item) {
                unset($loadParams[$item]);
            }
        }
        
        if ($c) {
            $slug = $this->buildUrl($c->slug, $appRequest->parameters); // composing address

            // creating url address
            $url = new Url($refUrl->getBaseUrl() . $slug);
            $url->setScheme($this->flags & self::SECURED ? 'https' : 'http');
            $url->setQuery($loadParams);
            return $url->getAbsoluteUrl();
        } else {
            // in case of detection according to domain is active, don't ask for the FORWARD method or Homepage presenter
            if ($this->configure['languageDomainSwitch'] && ($appRequest->method != 'FORWARD' || $appRequest->presenterName == 'Homepage')) {
                $url = new Url($refUrl->getBaseUrl());
                $url->setScheme($this->flags & self::SECURED ? 'https' : 'http');
                $url->setQuery($loadParams);
                return $url->getAbsoluteUrl();
            }
            return null;
        }
    }
}
