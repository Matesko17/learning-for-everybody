<?php

namespace App\AdminModule\Services;

use App\Model\Entities\Translation;
use App\Model\Facades\TranslationFacade;
use App\Services\SpreadsheetService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Kdyby\Translation\Translator;
use Nette\Neon\Neon;
use PhpOffice\PhpSpreadsheet\Exception as PSException;

/**
 * Class TranslationService.
 *
 * @author Kamil Walig <kamil.walig@q2.cz>
 * @author Jan Hermann <jan.hermann@q2.cz>
 * @package Qetteweb
 */
class TranslationService
{
    /** @var Translator */
    private $translator;
    
    /** @var TranslationFacade */
    private $translationFacade;
    
    /** @var string */
    private $translationPath;
    
    /** @var array */
    private $availableLanguages;
    
    /**
     * TranslationService constructor.
     * @param array $availableLanguages
     * @param string $translationPath
     * @param Translator $translator
     * @param TranslationFacade $translationFacade
     */
    public function __construct(
        array $availableLanguages,
        string $translationPath,
        Translator $translator,
        TranslationFacade $translationFacade
    )
    {
        $this->translator = $translator;
        $this->translationFacade = $translationFacade;
        $this->availableLanguages = array_merge((array)$translator->getDefaultLocale(), array_keys($availableLanguages));
        $this->translationPath = $translationPath;
    }
    
    /**
     * Creates translated entities and saves them
     * @throws ORMException
     */
    public function create()
    {
        $translations = $this->getMessages();
        $entities = $this->prepareToImport($translations);
        $this->translationFacade->importEntities($entities);
        $this->translationFacade->cleanUpTranslation();
    }
    
    /**
     * Returns translated messages from neon
     * @return array
     */
    private function getMessages(): array
    {
        $messages = [];
        foreach($this->availableLanguages as $lang) {
            $catalogue = $this->translator->getCatalogue($lang);
            $messages[$catalogue->getLocale()] = $catalogue->all();
        }
        
        return $messages;
    }
    
    /**
     * Prepares entities from messages
     * @param array $translations
     * @return array
     */
    private function prepareToImport(array $translations): array
    {
        $entities = [];
        $this->translationFacade->prepareEntitiesForImport();
        
        foreach($translations["xx"] as $namespace => $messages) {
            foreach($messages as $sections => $message) {
                list($section, $key) = explode('.', $sections, 2);
                
                foreach($this->availableLanguages as $lang) {
                    if($lang == "xx") {
                        continue;
                    }
                    
                    if($entity = $this->translationFacade->hasEntity($namespace, $section, $key, $lang)) {
                        $entity->setDefault($message)->setUpdated(1);
                    } else {
                        $entity = new Translation();
                        $entity->setNamespace($namespace)
                            ->setSection($section)
                            ->setKey($key)
                            ->setDefault($message)
                            ->setLang($lang)
                            ->setUpdated(1);
                    }
                    
                    if(!empty($translations[$lang][$namespace][$sections])) {
                        $entity->setTranslate($translations[$lang][$namespace][$sections]);
                    }
                    
                    $entities[] = $entity;
                }
            }
        }
        
        return $entities;
    }
    
    /**
     * Generates neon files from translation entities
     * @throws Exception
     */
    public function generate()
    {
        $translations = $this->translationFacade->getTranslations();
        if(!$translations) {
            throw new Exception('translations are not available, first create them with parameter: --create');
        }
        
        $generated = $this->prepareToGenerate($translations);
        foreach($generated as $namespace => $langs) {
            foreach($langs as $lang => $message) {
                $output = Neon::encode($message, Neon::BLOCK);
                $file = $this->translationPath.$namespace.'.'.$lang.'.neon';
                file_put_contents($file, $output);
            }
        }
    }
    
    /**
     * Creates neon php array from entities
     * @param array $translations
     * @return array
     */
    private function prepareToGenerate(array $translations): array
    {
        $generated = [];
        foreach($translations as $tr) {
            $value = $tr->getTranslate() ?? $tr->getDefault();
            $key = $tr->getNamespace().'.'.$tr->getLang().'.'.$tr->getSection().'.'.$tr->getKey();
            $this->assignArrayByPath($generated, $key, $value);
        }
        
        return $generated;
    }
    
    /**
     * @param $arr
     * @param $path
     * @param $value
     * @param string $separator
     */
    public function assignArrayByPath(&$arr, $path, $value, $separator = '.')
    {
        $keys = explode($separator, $path);
        
        foreach($keys as $key) {
            $arr = &$arr[$key];
        }
        
        $arr = $value;
    }
    
    /**
     * @param $filename
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PSException
     */
    public function import($filename)
    {
        $parser = new SpreadsheetService($filename);
        $data = $parser->parse();
        
        $entities = [];
        foreach($data as $d) {
            $entities[] = $this->translationFacade->createEntity($d);
        }
        $this->translationFacade->updateEntities($entities);
    }
}
