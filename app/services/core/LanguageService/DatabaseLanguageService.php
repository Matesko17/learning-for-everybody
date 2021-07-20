<?php

namespace App\Services;

use Dibi\Connection;
use Nette\Caching\IStorage;
use Nette\Caching\Cache;

/**
 * Class DatabaseLanguageService.
 * Database language service for DYNAMIC translations from database, for dynamicly extendable languages with row attitude.
 * It can be tied through Id (integer FK) or Code (string FK: cs, en).
 *
 * sql:
 * CREATE TABLE IF NOT EXISTS `netteweb`.`prefix_language` (
 * `Id` INT NOT NULL AUTO_INCREMENT,
 * `Code` VARCHAR(10) NOT NULL,
 * `Name` VARCHAR(50) NOT NULL,
 * PRIMARY KEY (`IdLang`))
 * ENGINE = InnoDB
 * COMMENT = 'jazyky'
 *
 * config:
 * databaseLanguageService: DatabaseLanguageService(%tb_language%, %mainLang%, %aliasLang%)
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
class DatabaseLanguageService extends AbstractLanguageService
{
    /**
     * DatabaseLanguageService constructor.
     * @param $tableLanguages
     * @param $mainLang
     * @param $aliasLang
     * @param Connection $database
     * @param IStorage $cacheStorage
     */
    public function __construct($tableLanguages, $mainLang, $aliasLang, Connection $database, IStorage $cacheStorage)
    {
        $cache = new Cache($cacheStorage, 'cache' . __CLASS__);
        $langs = $cache->load('langs');
        if ($langs === null) {
            // nacteni vsech jazyku do pole
            $langs = $database->select('id idLang, code, name')
                ->from($tableLanguages)
                ->orderBy('id')->asc()
                ->fetchAssoc('code'); // loads pair languageCode => array(id, name)
            $cache->save('langs', $langs); // caching without expiration
        }

        parent::__construct($langs, $mainLang, $aliasLang);
    }
}
