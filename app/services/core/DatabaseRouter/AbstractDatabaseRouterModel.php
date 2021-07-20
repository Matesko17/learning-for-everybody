<?php

namespace App\Services;

use dibi;
use Dibi\Connection;
use Dibi\Exception;
use Dibi\Result;
use Nette\Application\Routers\Route;
use Nette\Application\UI\Presenter;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\SmartObject;
use Nette\Utils\Strings;

/**
 * Class AbstractDatabaseRouterModel.
 * Service with row attitude.
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
abstract class AbstractDatabaseRouterModel
{
    use SmartObject;

    protected $tableRoute, $tableRouteAlias,
        $database, $cache, $language;
    /** @var DatabaseRouter */
    protected $databaseRouter;


    /**
     * AbstractDatabaseRouterModel constructor.
     * @param $tableRoute
     * @param Connection $database
     * @param AbstractLanguageService $language
     * @param IStorage $cacheStorage
     */
    public function __construct($tableRoute, Connection $database, AbstractLanguageService $language, IStorage $cacheStorage)
    {
        $this->tableRoute = $tableRoute;
        $this->tableRouteAlias = $tableRoute . '_alias';

        $this->database = $database;
        $this->language = $language;
        $this->cache = new Cache($cacheStorage, 'cache' . __CLASS__);
    }


    /**
     * Loading language service.
     * @return AbstractLanguageService
     */
    public function getLanguageService()
    {
        return $this->language;
    }


    /**
     * Internal route search.
     * @param $presenter
     * @param $action
     * @return mixed
     * @throws \Throwable
     */
    protected function getIdRoute($presenter, $action)
    {
        $cacheKey = 'getIdRoute' . $presenter . $action;
        $cursor = $this->cache->load($cacheKey);
        if ($cursor === null) {
            $cursor = $this->database->select('Id')
                ->from($this->tableRoute)
                ->where('presenter=%s', $presenter)
                ->where('action=%s', $action)
                ->fetchSingle();

            // saving cache
            $this->cache->save($cacheKey, $cursor, [
                Cache::TAGS => ['getIdRoute/' . $presenter . '/' . $action],
            ]);
        }
        return $cursor;
    }


    /**
     * Internal route setting.
     * @param $presenter
     * @param $action
     * @return bool|Result|int
     * @throws Exception
     */
    protected function insertRoute($presenter, $action)
    {
        if ($presenter && $action) {
            $values = [
                'presenter' => $presenter,
                'action' => $action,
            ];

            // removing cache for given route
            $this->cache->clean([
                Cache::TAGS => ['getIdRoute/' . $presenter . '/' . $action],
            ]);

            // inserting new route (presenter + action) to the route table
            return $this->database->insert($this->tableRoute, $values)
                ->execute(Dibi::IDENTIFIER);
        }
        return false;
    }


    /**
     * Internal alias search from slug.
     * @param $slug
     * @param null $parameters
     * @return mixed
     */
    protected function getIdRouteAlias($slug, $parameters = null)
    {
        // retrieving route
        $cursor = $this->database->select('r.id')
            ->from($this->tableRoute)->as('r')
            ->join($this->tableRouteAlias)->as('a')->on('r.id=a.route_id')
            ->where('slug=%s', $slug)
            ->where('language=%s', $this->language->getCode());

        if (isset($parameters['action'])) {
            $cursor->where('action=%s', $parameters['action']);
        }

        if (isset($parameters['id'])) {
            $cursor->where('item=%i', $parameters['id']);
        }
        return $cursor->fetchSingle();
    }


    /**
     * Loading presenter and action from slug.
     * - match
     * @param $slug
     * @return mixed
     * @throws \Throwable
     */
    public function getBySlug($slug)
    {
        $lang = $this->language->getCode(); // uses internal language setting

        $cacheKey = 'match-' . $lang . $slug;
        $cursor = $this->cache->load($cacheKey);
        if ($cursor === null) {
            $cursor = $this->database->select('r.id, presenter, action, language, slug, item, parameters, a.id as aliasId, a.added as aliasAdded')
                ->from($this->tableRoute)->as('r')
                ->join($this->tableRouteAlias)->as('a')->on('r.id=a.route_id')
                ->where('slug=%s', $slug)
                ->where('language=%s', $lang)
                ->fetch();

            // saving cache
            $this->cache->save($cacheKey, $cursor, [
                Cache::TAGS => [$lang . '/' . $slug],
            ]);
        }
        return $cursor;
    }


    /**
     * Checking that route has younger route (admin purposes).
     */
    public function hasOtherYoungerRecord($r)
    {
        $lang = $this->language->getCode();
        $cursor = $this->database->select('r.id')
            ->from($this->tableRoute)->as('r')
            ->join($this->tableRouteAlias)->as('a')->on('r.id=a.route_id')
            ->where('presenter=%s', $r->presenter)
            ->where('action=%s', $r->action)
            ->where('item = %i', $r->item)
            ->where('a.added > %t', $r->aliasAdded)
            ->where('language=%s', $lang)
            ->fetchAll();
        return $cursor;
    }


    /**
     * Updating route record (admin purposes).
     * @param Route $r record
     * @return bool
     * @throws Exception
     */
    public function updateRouteRecord($r)
    {
        $up = array('added' => date('Y-m-d H:i:s'));
        return $this->database->update($this->tableRouteAlias, $up)
            ->where('id = %i', $r->aliasId)->execute();
    }


    /**
     * Internal loading language code from parameter or language service.
     * @param $parameters
     * @return int
     */
    protected function getLangCode($parameters)
    {
        // loading language from parameter (+ translate into id) or loading from internal settings
        if (isset($parameters['locale'])) {
            return $parameters['locale'];
        } else {
            return $this->language->getCode();
        }
    }


    /**
     * Loading slug by given presenter and parameter.
     * - constructUrl
     * @param $presenter
     * @param $parameters
     * @return mixed|null
     * @throws \Throwable
     */
    public function getSlugByParams($presenter, $parameters)
    {
        $lang = $this->getLangCode($parameters); // accepts lang parameter
        $action = (isset($parameters['action']) ? $parameters['action'] : null);
        $id = (isset($parameters['id']) ? $parameters['id'] : null);

        $cacheKey = 'constructUrl-' . $lang . $presenter . $action . $id;
        $cursor = $this->cache->load($cacheKey);
        if ($cursor === null) {
            $cursor = $this->database->select('r.id, slug, item, parameters')
                ->from($this->tableRoute)->as('r')
                ->join($this->tableRouteAlias)->as('a')->on('r.id=a.route_id')
                ->where('presenter=%s', $presenter)
                ->where('language=%s', $lang)
                ->orderBy('added')->desc(); // always takes the newest version

            // searching by action
            if ($action) {
                $cursor->where('action=%s', $action);
            }

            // additional searchong by id
            if ($id) {
                $cursor->where('item=%i', $id);
            }

            $cursor = $cursor->fetch();

            // saving cache
            $this->cache->save($cacheKey, $cursor, [
                Cache::TAGS => [$lang . ':' . $presenter . ':' . $action . ':' . $id],
            ]);
        }
        return $cursor;
    }


    /**
     * Accuracy od database routeru instance.
     * @param DatabaseRouter $databaseRouter
     */
    public function setDatabaseRouter(DatabaseRouter $databaseRouter)
    {
        $this->databaseRouter = $databaseRouter;
    }


    /**
     * Manual route insert.
     * @param $presenter
     * @param $action
     * @param $slug
     * @param array $parameters
     * @return $this
     * @throws \Throwable
     */
    public function createRoute($presenter, $action, $slug, $parameters = [])
    {
        $this->insertSlug(new InternalRouterPresenter($presenter, $action, $parameters), $slug);
        return $this;
    }


    /**
     * Inserting slug to the database using SlugCreator component
     * @param Presenter $context
     * @param $slug
     * @return Result|int|mixed
     * @throws \Throwable
     */
    public function insertSlug(Presenter $context, $slug)
    {
        try {
            if ($this->databaseRouter) {
                $params = $context->getParameters();
                // setting variables for later usage
                $lang = $this->getLangCode($params); // accepts lang parameter
                $presenter = $context->getName();
                $action = $context->getParameter('action');
                $parameters = array_filter($params); // takes all parameters and filter out null records
                $newSlug = Strings::webalize($slug, '/'); // slug webalize and '/' ignoring

                $idRoute = $this->getIdRoute($presenter, $action); // route duplicity check


                if (!$idRoute) {
                    // if insterted successfully. load IdRoute again for next query
                    $idRoute = $this->insertRoute($presenter, $action);
                }

                $idItem = (isset($parameters['id']) ? $parameters['id'] : null);

                // insterting into route_alias table (idRoute + language + slug)
                $idAlias = $this->getIdRouteAlias($newSlug, $parameters); // duplicity check (name + slug language)

                // set admin route
                $route = $this->getBySlug($newSlug);
                if($route && $this->hasOtherYoungerRecord($route)){
                    $this->updateRouteRecord($route);
                }

                // parameter filtering
                $parameters = $this->databaseRouter->filterParameters($parameters);
                $params = ($parameters ? serialize($parameters) : null);

                // cache cleaning for slugs
                $this->cache->clean([
                    Cache::TAGS => [$lang . '/' . $newSlug],
                ]);

                // cache cleaning for presenters
                $this->cache->clean([
                    Cache::TAGS => [$lang . ':' . $presenter . ':' . $action . ':' . $idItem],
                ]);

                $ret = 0;

                // non/exists one/more
                if (strpos($slug, '##') === false && !$idAlias) { // slug can't contain ## at the beginning and can't exist in database
                    $values2 = [
                        'route_id' => $idRoute,
                        'language' => $lang,
                        'slug' => $newSlug, // only slug without language and paging can be inserted
                        'item' => $idItem,
                        'parameters' => $params,
                        'added' => new \DateTime,
                    ];
                    $ret = $this->database->insert($this->tableRouteAlias, $values2)
                        ->execute(Dibi::IDENTIFIER);
                }
                return $ret;
            }
            return null;
        } catch (Exception $e) {
            return -$e->getCode();
        }
    }
}
