<?php

namespace App\Services;

use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\InvalidArgumentException;
use App\Model\Facades\SiteSettingFacade;
use Doctrine\DBAL\Exception\TableNotFoundException;

/**
 * Class SiteSettingService.
 *
 * @author  Kamil Walig <kamil.walig@q2.cz>
 * @author  Jakub Markus <jakub.markus@q2.cz>
 * @package Qetteweb
 */
class SiteSettingService
{
    /** @var  array */
    private $parameters;

    /**
     * @var SiteSettingFacade
     */
    private $siteSettingFacade;

    /**
     * @var array
     */
    private $dbConfig = array();

    /**
     * @var array
     */
    private $config;

    public function __construct(
        $parameters,
        $cachePath,
        SiteSettingFacade $siteSettingFacade,
        IStorage $storage
    )
    {
        $this->parameters = $parameters;
        $this->siteSettingFacade = $siteSettingFacade;
        $this->cache = new Cache($storage, 'site-setting');
        $this->build();
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key, $lang = NULL)
    {
        if ($lang === NULL) {
            $value = $this->config[$key][null];
        } else {
            $value = isset($this->config[$key][$lang]) ? $this->config[$key][$lang] : $this->config[$key][null];
        }

        return $value;
    }

    /**
     * Build site config.
     * @throws InvalidArgumentException
     * @throws Exception
     */
    private function build()
    {
        foreach ($this->parameters as $key => $value) {
            $this->parameters[$key] = array(NULL => $value);
        }

        $this->config = $this->cache->load('db-settings');
        if ($this->config === NULL) {

            try {
                $siteSettings = $this->siteSettingFacade->getSiteSettings();
            }catch (TableNotFoundException $e) {
                $this->dbConfig = [];
                $siteSettings = [];
            }catch (Exception $e){
                throw $e;
            }

            foreach ($siteSettings as $entity) {
                if ($this->isJSON($entity->getValue())) {
                    $value = json_decode($entity->getValue(), true);
                } else {
                    $value = $entity->getValue();
                }
                if($entity->getLang() === NULL) {
                    $this->dbConfig[$entity->getKey()][NULL] = $value;
                }else{
                    $this->dbConfig[$entity->getKey()][NULL] = $this->parameters[$entity->getKey()][NULL];
                    $this->dbConfig[$entity->getKey()][$entity->getLang()] = $value;
                }
            }
            $this->config = array_merge($this->parameters, $this->dbConfig);
            $this->cache->save('db-settings', $this->config);
        }
    }

    private function isJSON($string)
    {
        return (is_string($string) && is_array(json_decode($string, true)) && (json_last_error() === JSON_ERROR_NONE));
    }


}