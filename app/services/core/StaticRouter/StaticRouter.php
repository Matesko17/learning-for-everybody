<?php

namespace App\Services;

use Nette\SmartObject;
use Nette\Application\IRouter;
use Nette\Http\IRequest;
use Nette\Application\Request;
use Nette\DI\Container;
use Nette\Utils\Strings;
use Nette\Http\Url;

/**
 * Class StaticRouter
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
class StaticRouter implements IRouter
{
    use SmartObject;

    private $metadata, $flags;
    /** @var AbstractLanguageService */
    private $language;
    private $slugs = null;


    /**
     * StaticRouter constructor.
     * @param Container $context
     * @param $slugs
     * @param array $metadata
     * @param int $flags
     */
    public function __construct(Container $context, $slugs, $metadata = [], $flags = 0)
    {
        $this->slugs = $slugs;
        $this->language = $context->getByType('AbstractLanguageService');
        $this->metadata = $metadata;
        $this->flags = $flags;
    }


    /**
     * Maps HTTP request to a Request object.
     * @param IRequest $httpRequest
     * @return Request|null
     */
    public function match(IRequest $httpRequest)
    {
        $slug = $httpRequest->getUrl()->getPathInfo();
        $presenter = null;
        $parameters = [];

        // inserting default values in case of empty address
        if (!$slug && $this->metadata) {
            $parameters = $this->metadata; // vlozeni parametru z metadat
            // if presenter is defined, pass it on and unset index
            if (isset($parameters['presenter'])) {
                $presenter = $parameters['presenter'];
                unset($parameters['presenter']);
            }
        }

        // language and slug separation
        $separate = Strings::match($slug, '/(?<lang>[a-z]{2}\/)?(?<slug>[[:alnum:]\-\_\/]+)/');

        // language and system setting separation
        $lang = $this->language->getMainLang();
        if (isset($separate['lang']) && $separate['lang']) {
            $lang = trim($separate['lang'], '/_');
        }
        $this->language->setLang($lang);

        // loading separated slug
        $separeSlug = (isset($separate['slug']) ? $separate['slug'] : null);

        if ($separeSlug) {
            if (isset($this->slugs[$lang][$separeSlug])) {
                $match = Strings::match($this->slugs[$lang][$separeSlug], '/(?<presenter>[[:alnum:]]{2,})(:(?<action>[[:alnum:]]+))?/');

                // setting presenter
                $presenter = $match['presenter'];

                // parameters passing on
                $parameters = [
                    'lang' => $lang,
                    'action' => (isset($match['action']) ? $match['action'] : null),
                ];
            } else {
                return null;
            }
        }

        // getting external parameters
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
     */
    public function constructUrl(Request $appRequest, Url $refUrl)
    {
        if ($this->flags & self::ONE_WAY) {
            return null;
        }

        $params = $appRequest->parameters;
        $adr = $appRequest->presenterName . ':' . (isset($params['action']) ? $params['action'] : '');

        $slug = null;
        $lang = isset($params['lang']) ? $params['lang'] : null;
        if (isset($this->slugs[$lang])) {
            $slug = array_search($adr, $this->slugs[$lang], true);
        }

        if ($slug) {
            // in case of non-main language, that language nedd to be used
            if ($lang != $this->language->getMainLang()) {
                $slug = $lang . '/' . $slug;
            }
            // removing parameters
            unset($params['lang'], $params['action']);

            $url = new Url($refUrl->getBaseUrl() . $slug);
            $url->setScheme($this->flags & self::SECURED ? 'https' : 'http');
            $url->setQuery($params);
            return $url->getAbsoluteUrl();
        } else {
            return null;
        }
    }
}
