<?php

namespace App\Services;

/**
 * Class DatabaseTranslatorColumns.
 * Database translator with support of plural with column attitude.
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
class DatabaseTranslatorColumns extends AbstractDatabaseTranslator
{
    /**
     * Internal loading translates from database.
     * @return array
     */
    protected function loadTranslate()
    {
        $code = $this->language->getCode(true);
        return $this->database->select('Id, Ident, ' . $code)
            ->from($this->tableTranslate)
            ->fetchPairs('Ident', $code);
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
        if ($code != $this->language->getMainLang()) {
            $message = sprintf('## %s ##', $message); // default translate text
        }

        $arr = [
            'Ident' => $index, // saving identifier
            'Sample' => $message, // saving original text
            $this->language->getCode(true) => $message, // saving into language shortcut
        ];

        // sample is savind just for the main language
        if ($code != $this->language->getMainLang()) {
            unset($arr['Sample']);
        }

        $this->database->query('INSERT INTO %n %v ON DUPLICATE KEY UPDATE %a', $this->tableTranslate, $arr, $arr); // insert/update in database
        $this->dictionary[$index] = $message; // adding associative array into dictionary
        $this->saveCache();

        // returning index
        return $message;
    }
}
