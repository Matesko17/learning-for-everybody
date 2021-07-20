<?php

namespace App\Services;

/**
 * Class DatabaseTranslator.
 * Database translator with support of plural with row attitude.
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
class DatabaseTranslator extends AbstractDatabaseTranslator
{
    /**
     * Internal loading translation from database.
     * @return array
     */
    protected function loadTranslate()
    {
        $code = $this->language->getCode();
        return $this->database->select('Id, Ident, Translate')
            ->from($this->tableTranslate)
            ->where('Language=%s', $code)
            ->fetchPairs('Ident', 'Translate');
    }


    /**
     * Internal saving single translation into database.
     * @param $index
     * @param $message
     * @return null|string
     */
    protected function saveTranslate($index, $message)
    {
        $code = $this->language->getCode();
        $original = $message;
        if ($code != $this->language->getMainLang()) {
            $message = sprintf('## %s ##', $message); // default translate text
        }

        $arr = [
            'Language' => $code,
            'Ident' => $index, // saving identifier
            'Sample' => $original, // saving original text
            'Translate' => $message, // saving into language shortcut
        ];

        $this->database->insert($this->tableTranslate, $arr)
            ->execute();
        $this->dictionary[$index] = $message; // adding associative array into dictionary
        $this->saveCache();

        // returning index
        return $message;
    }
}
