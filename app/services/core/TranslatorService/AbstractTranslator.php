<?php

namespace App\Services;

use Nette\Localization\ITranslator;
use Nette\SmartObject;
use Nette\Utils\Strings;
/**
 * Class AbstractTranslator.
 * Abstract sceleton of the class.
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
abstract class AbstractTranslator implements ITranslator
{
    use SmartObject;

    protected $language, $dictionary, $plurals;


    /**
     * AbstractTranslator constructor.
     * @param null $plurals
     * @param AbstractLanguageService $language
     */
    protected function __construct($plurals = null, AbstractLanguageService $language)
    {
        $this->plurals = $plurals;
        $this->language = $language;
    }


    /**
     * setting|adding plural form
     * example: array('cs' => array('$plural=(n==1) ? 0 : ((n>=2 && n<=4) ? 1 : 2);'))
     * source: http://docs.translatehouse.org/projects/localization-guide/en/latest/l10n/pluralforms.html
     * @param array $plurals
     * @return $this
     */
    public function setPlurals(array $plurals)
    {
        if ($this->plurals) {
            $this->plurals += $plurals;
        } else {
            $this->plurals = $plurals;
        }
        return $this;
    }


    /**
     * Translates the given string.
     * @param $message
     * @param null $count
     * @return null|string
     */
    public function translate($message, $count = NULL)
    {
        $indexDictionary = md5(Strings::webalize($message) . $message); // calculation of unique index for array

        if ($message) {
            $code = $this->language->getCode();
            if (!isset($count) || !isset($this->plurals[$code])) { // if count is not set or plurals is not set
                if (!isset($this->dictionary[$indexDictionary])) {
                    return $this->saveTranslate($indexDictionary, $message); // creation
                }
            } else {
                // serving pure substitution, if array is not empty and first index is NULL
                if (isset($count) && is_array($count) && is_null($count[0])) {
                    if (!isset($this->dictionary[$indexDictionary])) {
                        return $this->saveTranslate($indexDictionary, $message); // creation
                    } else {
                        return vsprintf($this->dictionary[$indexDictionary], array_slice($count, 1)); // substitution from 1st index
                    }
                }

                // serving plural substitution
                if (isset($this->plurals[$code])) {
                    $plural = null; // output variable of plural type
                    $n = (is_array($count) ? $count[0] : $count); // input variable of count (if its an array, it takes index: [0])
                    eval($this->plurals[$code]); // plural evaluation
                    $pluralFormat = '%s:plural:%d'; // plural format
                    $pluralIndex = sprintf($pluralFormat, $indexDictionary, $plural); // $index . ':plural:' . intval($plural); // composing of extended index
                    if (!isset($this->dictionary[$pluralIndex])) {
                        // multiple inserting of plural format according to the count ($nplurals)
                        if (isset($nplurals)) {
                            // saving all plurals at once
                            for ($i = 0; $i < $nplurals; $i++) {
                                $this->saveTranslate(sprintf($pluralFormat, $indexDictionary, $i), $message); // creation of all plurals
                            }
                            return $message;
                        } else {
                            return $this->saveTranslate($pluralIndex, $message); // creation of given plural
                        }
                    } else {
                        if (is_array($count)) { // in case of array, it uses vsprintf
                            // multiple substitution of array
                            return vsprintf($this->dictionary[$pluralIndex], $count); // array
                        }
                        // parameter substition
                        return sprintf($this->dictionary[$pluralIndex], $count); // parameter
                    }
                }
            }
            return $this->dictionary[$indexDictionary];
        }
        return NULL;
    }
}
