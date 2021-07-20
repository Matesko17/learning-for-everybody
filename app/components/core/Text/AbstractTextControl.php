<?php

namespace App\Components;

use App\Model\Entities\Text;
use Nette\Application\UI\Control;
use Nette\Caching\IStorage;
use Nette\Caching\Cache;
use Dibi\Connection;
use Dibi\Result;
use Dibi\Exception;
use Kdyby\Translation\Translator;
use InvalidArgumentException;
use IInvalidateCache;
use App\Services\AbstractLanguageService;
use App\Model\Facades\TextFacade;
use Nette\Utils\Strings;

/**
 * Class AbstractTextControl.
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @author  Petr Gräf <petr.graf@q2.cz>
 * @package Qetteweb
 */
abstract class AbstractTextControl extends Control implements IInvalidateCache
{
    protected $database, $textFacade, $cache, $texts, $langCode, $translator, $extraColumns = null;
    
    /**
     * AbstractTextControl constructor.
     * @param Connection $database
     * @param TextFacade $textFacade
     * @param AbstractLanguageService $language
     * @param IStorage $cacheStorage
     * @param Translator $translator
     */
    public function __construct(Connection $database, TextFacade $textFacade, AbstractLanguageService $language, IStorage $cacheStorage, Translator $translator)
    {
        parent::__construct();
        $this->database = $database;
        $this->textFacade = $textFacade;
        $this->cache = new Cache($cacheStorage, 'cache' . get_class($this));
        $this->langCode = $language->getCode();
        $this->translator = $translator;
    }


    /**
     * Cache invalidate hook.
     */
    public function hookInvalidateCache()
    {
        // force recache
        $this->cache->clean([
            Cache::TAGS => ['loadText'],
        ]);
    }


    /**
     * Setting extra columns.
     * @param $extraColumns
     * @return $this
     */
    public function setExtraColumns(array $extraColumns)
    {
        $this->extraColumns = $extraColumns;
        return $this;
    }


    /**
     * Retrieving data from user defined custom extra columns
     * @param string $ident
     * @param string $column
     * @return string
     * @throws InvalidArgumentException
     */
    public function getColumnData($ident, $column)
    {
        if(!isset($ident)) {
            throw new InvalidArgumentException($this->translator->translate('components.textControl.noArgument'));
        }

        $text = $this->loadText($ident);

        $getFunction = 'get'.Strings::firstUpper($column);

        // we need to figure out, how to do the check using q2/doctrine-behaviors Translatable
        // if(!isset($this->texts[$ident]->$column)) {
        //     throw new InvalidArgumentException($this->translator->translate('components.textControl.columnNotFound', ['column' => $column]));
        // }

        return $text->$getFunction();
    }


    /**
     * @param null|string $ident
     *
     * @return string
     */
    protected function loadText($ident = null)
    {
        $key = 'texts-' . $ident . '-' . $this->langCode;

        // load text from cache
        $text = $this->cache->load($key, function() use ($ident, $key) {
            $textObj = $this->textFacade->getTextByIdent($ident);

            // if is new text
            if (!($textObj instanceof Text)) {
                $textObj = $this->addText($ident);
                $this->cache->clean([
                    Cache::TAGS => ['loadText'],
                ]);
            }

            // save text to cache
            $this->cache->save($key, $textObj->translate($this->langCode), [
                Cache::EXPIRE => '30 minutes',
                Cache::TAGS => ['loadText'],
            ]);

            return $textObj->translate($this->langCode);
        });

        return $text;
    }


    /**
     * Adding text for given ident.
     * @param $ident
     * @return Result|int|mixed|null
     */
    private function addText($ident)
    {
        try {
            return $this->textFacade->createNew($ident);
        } catch (Exception $e) {
            return -$e->getCode();
        }
    }


    public function renderTitle($ident)
    {
        echo $this->getColumnData($ident, 'title');
    }


    public function renderContent($ident)
    {
        echo $this->getColumnData($ident, 'content');
    }
}
