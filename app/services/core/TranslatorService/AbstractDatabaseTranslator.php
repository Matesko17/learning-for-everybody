<?php

namespace App\Services;

use Dibi\Connection;
use Nette\Caching\IStorage;
use Nette\Caching\Cache;

/**
 * Class AbstractDatabaseTranslator.
 * Database translator with support of plural.
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
abstract class AbstractDatabaseTranslator extends AbstractTranslator
{
    private $cache, $cacheKey;
    protected $database, $tableTranslate;


    /**
     * DatabaseTranslator constructor.
     * @param null $tableTranslate
     * @param null $plurals
     * @param Connection $database
     * @param AbstractLanguageService $language
     * @param IStorage $cacheStorage
     */
    public function __construct($tableTranslate, $plurals = null, Connection $database, AbstractLanguageService $language, IStorage $cacheStorage)
    {
        parent::__construct($plurals, $language);

        $this->database = $database;
        $this->tableTranslate = $tableTranslate;
        $this->plurals = $plurals;

        $this->cache = new Cache($cacheStorage, 'cache' . __CLASS__);
        $this->language = $language;

        // klic pro cache
        $this->cacheKey = 'dictionary' . $this->language->getId();

        // nacteni prekladu
        $this->initTranslate();
    }


    /**
     * Internal loading translate library.
     */
    private function initTranslate()
    {
        $this->dictionary = $this->cache->load($this->cacheKey);
        if ($this->dictionary === null) {
            $this->dictionary = $this->loadTranslate();
            $this->saveCache();
        }
    }


    /**
     * Invalidate cache hook.
     */
    public function hookInvalidateCache()
    {
        $this->cache->remove($this->cacheKey); // force recache
        // force recache
        $this->cache->clean([
            Cache::TAGS => ['saveCache'],
        ]);
    }


    /**
     * Internal saving cache.
     */
    protected function saveCache()
    {
        $this->cache->save($this->cacheKey, $this->dictionary, [
            Cache::EXPIRE => '30 minutes',
            Cache::TAGS => ['saveCache'],
        ]);
    }
}
