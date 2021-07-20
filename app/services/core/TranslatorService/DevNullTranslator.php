<?php

namespace App\Services;

/**
 * Class DevNullTranslator.
 * DevNull translator with support of plural substitution and support of separate substitution.
 * bez uloziste
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
class DevNullTranslator extends AbstractTranslator
{
    /**
     * DevNullTranslator constructor.
     * @param null $plurals
     * @param AbstractLanguageService $language
     */
    public function __construct($plurals = null, AbstractLanguageService $language)
    {
        parent::__construct($plurals, $language);
    }


    /**
     * Translates the given string.
     * @param $message
     * @param null $count
     * @param null $plurals
     * @return null|string
     */
    public function translate($message, $count = null, $plurals = null)
    {
        $code = $this->language->getCode();
        if (isset($this->plurals[$code]) && isset($count) && isset($plurals)) {
            $plural = null; // output variable of plural type
            $n = $count; // passing on number of items
            eval($this->plurals[$code]); // plural evaluation
            return sprintf($plurals[$plural], $count); // election of right index from plural array
        }

        if (is_array($count)) { // in case of array, it uses vsprintf
            // multiple substitution of array
            return vsprintf($message, $count); // array
        }
        // substituce parametru
        return sprintf($message, $count); // parameter
    }
}
