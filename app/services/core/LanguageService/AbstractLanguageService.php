<?php

namespace App\Services;

use Nette\SmartObject;

/**
 * Class AbstractLanguageService
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
abstract class AbstractLanguageService
{
    use SmartObject;

    protected $languages, $nameLanguages, $aliasLang;
    protected $idLang = -1, $mainLang = null, $code = null, $lang = null;


    /**
     * AbstractLanguageService constructor.
     * @param $languages
     * @param $mainLang
     * @param $aliasLang
     */
    protected function __construct($languages, $mainLang, $aliasLang)
    {
        // naparsrovani id jazyku
        $this->languages = array_map(function ($row) {
            return $row['idLang'];
        }, $languages);
        // naparsrovani jmena jazyku
        $this->nameLanguages = array_map(function ($row) {
            return $row['name'];
        }, $languages);

        $this->mainLang = $mainLang;
        $this->aliasLang = $aliasLang;

        $this->setLang($mainLang);
    }


    /**
     * Language code check and load.
     */
    private function checkLang()
    {
        $code = null;
        $idLang = null;
        if ($this->lang) { // takes 0th index from the request
            // searches available languages
            if (isset($this->languages[$this->lang])) {
                $code = $this->lang;
                $idLang = $this->languages[$code];
            } else {
                // in case of language is not matched, searches in aliases
                $idLang = $this->getExistId($this->lang);
                $flip = array_flip($this->languages);
                $code = $flip[$idLang]; // matched language id is used as a code
            }
        } else {
            // in case the language is not passed from address, uses the main language
            $code = $this->mainLang;
            $idLang = $this->languages[$code];
        }

        // passing into instance
        $this->code = $code;
        $this->idLang = $idLang;
    }


    /**
     * Actual language getter.
     * @return null
     */
    public function getLang()
    {
        return $this->lang;
    }


    /**
     * Actual language setter.
     * @param $lang
     */
    public function setLang($lang)
    {
        if ($lang) {
            $this->lang = strtolower($lang);
            $this->checkLang();
        }
    }


    /**
     * Get available languages.
     * @return array
     */
    public function getAvailableLanguages()
    {
        return $this->languages;
    }


    /**
     * Get names of available languages.
     * @return array
     */
    public function getNameLanguages()
    {
        return $this->nameLanguages;
    }


    /**
     * Get actual language id.
     * @return int
     */
    public function getId()
    {
        return $this->idLang;
    }


    /**
     * Get main language id.
     * @return null
     */
    public function getIdMainLang()
    {
        return (isset($this->languages[$this->mainLang]) ? $this->languages[$this->mainLang] : null);
    }


    /**
     * Get actual language code.
     * @param bool $upper
     * @return null|string
     */
    public function getCode($upper = false)
    {
        return ($upper ? strtoupper($this->code) : $this->code);
    }


    /**
     * Get main language.
     * @param bool $upper
     * @return null|string
     */
    public function getMainLang($upper = false)
    {
        return ($upper ? strtoupper($this->mainLang) : $this->mainLang);
    }


    /**
     * Checks that given language exists.
     * At first it searches languages and then aliases (recursively).
     * @param $code
     * @return bool
     */
    public function isExists($code)
    {
        if (isset($this->languages[$code])) {
            return true;
        }

        if (isset($this->aliasLang[$code])) {
            return $this->isExists($this->aliasLang[$code]);
        }
        return false;
    }


    /**
     * Loading language id.
     * At first it searches languages and then aliases (recursively).
     * @param $code
     * @return mixed
     */
    public function getExistId($code)
    {
        if (isset($this->languages[$code])) {
            return $this->languages[$code];
        }

        if (isset($this->aliasLang[$code])) {
            return $this->getExistId($this->aliasLang[$code]);
        }
        return $this->languages[$this->mainLang];
    }
}
