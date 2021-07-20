<?php

namespace App\Components;

use Nette\Application\UI\Control;
use Tracy\Debugger;
use App\Services\SiteSettingService;

/**
 * Class GaCodeControl pro Google Analytics
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @package Qetteweb
 */
class GaCodeControl extends Control
{
    /**
     * @var SiteSettingService
     */
    private $siteSettingService;
    /**
     * @var string
     */
    private $lang;

    /**
     * Default constructor.
     * @param $lang
     * @param SiteSettingService $siteSettingService
     */
    public function __construct(SiteSettingService $siteSettingService)
    {
        $this->siteSettingService = $siteSettingService;
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * Default render method.
     */
    public function render()
    {
        $ga = $this->siteSettingService->get('ga', $this->lang);

        if ($ga && !empty($ga) && Debugger::$productionMode) {
            echo <<<GA
        <script type='text/javascript'>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', '{$ga}', 'auto');
            ga('require', 'displayfeatures');
            ga('send', 'pageview');
        </script>
GA;
        } else {
            echo <<<GA
        <!-- Google Analytics: {$ga} -->
GA;
        }
    }
}
