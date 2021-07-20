<?php

namespace App\Services;

/**
 * Class StaticLanguageService.
 * Static language service for STATIC translations, for staticaly defined languages.
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
class StaticLanguageService extends AbstractLanguageService
{
    /**
     * StaticLanguageService constructor.
     * @param SiteSettingService $siteSettingService
     */
    public function __construct(SiteSettingService $siteSettingService)
    {
        $pocId = 0;
        $langs = array_map(function ($row) use (&$pocId) {
            $pocId++;
            return [
                'idLang' => $pocId,
                'name' => $row,
            ];
        }, $siteSettingService->get('allowLang')); // loads pair languageCode => array(id, name)
        parent::__construct($langs, $siteSettingService->get('mainLang'), $siteSettingService->get('aliasLang'));
    }
}
