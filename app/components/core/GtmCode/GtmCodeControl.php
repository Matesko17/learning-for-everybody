<?php

namespace App\Components;

use Nette\Application\UI\Control;
use App\Services\SiteSettingService;
use Tracy\Debugger;

/**
 * Class GtmCodeControl for Google Tag Manager
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
class GtmCodeControl extends Control
{
    /**
     * @var SiteSettingService
     */
    private $siteSettingService;

    /**
     * Default constructor.
     * @param SiteSettingService $siteSettingService
     */
    public function __construct(SiteSettingService $siteSettingService)
    {
        $this->siteSettingService = $siteSettingService;
    }


    /**
     * Default render method.
     */
    public function renderHead()
    {
        // loading ga from configuration
        $gtm = $this->siteSettingService->get('gtm');

        if ($gtm && !empty($gtm) && Debugger::$productionMode) {
            echo <<<GA
        <!-- Google Tag Manager head -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{$gtm}');</script>
        <!-- End Google Tag Manager head -->

GA;
        } else {
            echo <<<GA
        <!-- Google Tag Manager head -->

GA;
        }
    }

    public function renderBody()
    {
        // loading ga from configuration
        $gtm = $this->siteSettingService->get('gtm');

        if ($gtm && Debugger::$productionMode) {
            echo <<<GA
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={$gtm}"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->

GA;
        } else {
            echo <<<GA
        <!-- Google Tag Manager body -->

GA;
        }
    }
}