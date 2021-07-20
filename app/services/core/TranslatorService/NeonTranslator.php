<?php

namespace App\Services;

use Nette\Neon\Neon;
/**
 * Class NeonTranslator.
 * Neon translator with support of plural.
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
class NeonTranslator extends AbstractTranslator
{
    private $path;


    /**
     * NeonTranslator constructor.
     * @param null $path
     * @param null $plurals
     * @param AbstractLanguageService $language
     */
    public function __construct($path, $plurals = null, AbstractLanguageService $language)
    {
        parent::__construct($plurals, $language);

        $this->plurals = $plurals;
        $this->language = $language;

        // path creation
        $this->path = $path . '/dictionary-' . $this->language->getLang() . '.neon';

        $this->loadDictionary(); // translate loading
    }


    /**
     * Translate loading.
     */
    private function loadDictionary()
    {
        if (file_exists($this->path)) {
            $this->dictionary = Neon::decode(file_get_contents($this->path));
        }
    }


    /**
     * Saving translation.
     * @param array $dictionary
     */
    private function saveDictionary(array $dictionary)
    {
        file_put_contents($this->path, Neon::encode($dictionary, Neon::BLOCK));
    }


    /**
     * Saving single translations.
     * @param $index
     * @param $message
     * @return string
     */
    protected function saveTranslate($index, $message)
    {
        if ($this->language->getCode() != $this->language->getMainLang()) {
            $message = sprintf('## %s ##', $message); // default translate text
        }

        // inserting translate into array
        $this->dictionary[$index] = $message;
        // saving to file
        $this->saveDictionary($this->dictionary);

        // returning text
        return $message;
    }
}
