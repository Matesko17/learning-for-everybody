<?php

namespace App\Services;

use Nette\Application\UI\Presenter;
use Dibi\Fluent;
use Dibi\Row;

/**
 * Class DatabaseRouterSeoModel
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
class DatabaseRouterSeoModel extends AbstractDatabaseRouterModel
{
    /**
     * Inserting and loading SEO information.
     * @param Presenter $context
     * @return Fluent|Row|FALSE|null
     */
    public function getSeo(Presenter $context)
    {
        $tableRouteSeo = $this->tableRoute . '_seo';

        // loading data from context
        $presenter = $context->getName();
        $action = $context->getParameter('action');
        $id = $context->getParameter('id');

        $idRoute = $this->getIdRoute($presenter, $action); // loading id router
        if (!$idRoute) {
            $idRoute = $this->insertRoute($presenter, $action);
        }

        // catching extreme states
        if (!$idRoute || in_array($presenter, ['Error', 'Error4xx'])) {
            return null;
        }

        // loading language from the service
        $lang = $this->language->getCode();

        $cursor = $this->database->select('id, title, description, keywords')
            ->from($tableRouteSeo)
            ->where('route=%i', $idRoute)
            ->where('language=%s', $lang);

        // in case of id is defined
        if ($id) {
            $cursor->where('item=%i', $id);
        } else {
            $cursor->where('item IS NULL');
        }

        $cursor = $cursor->fetch();

        if (!$cursor) {
            // insterting an empty record
            $values = [
                'route' => $idRoute,
                'item' => $id,
                'language' => $lang,
            ];
            $this->database->insert($tableRouteSeo, $values)
                ->execute();
        } else {
            // returning loaded record
            return $cursor;
        }
        return null;
    }
}
