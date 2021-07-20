<?php

namespace App\Components;

use App\Model\Facades\TextFacade;
use App\Services\AbstractLanguageService;
use Dibi\Connection;
use Kdyby\Translation\Translator;
use Nette\Caching\IStorage;

/**
 * Class TextControl
 * Component for filling text content of the web (title + content)
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
class TextControl extends AbstractTextControl
{
    /**
     * TextControl constructor.
     * @param Connection $database
     * @param TextFacade $textFacade
     * @param AbstractLanguageService $language
     * @param IStorage $cacheStorage
     * @param Translator $translator
     */
    public function __construct(Connection $database, TextFacade $textFacade, AbstractLanguageService $language, IStorage $cacheStorage, Translator $translator)
    {
        parent::__construct($database, $textFacade, $language, $cacheStorage, $translator);

        // explicit definition of basic columns
        $this->setExtraColumns(['title', 'content']);
    }
}
