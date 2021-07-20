<?php

namespace App\Presenters;

use App\Components\CssControl;
use App\Components\GaCodeControl;
use App\Components\GtmCodeControl;
use App\Components\ILanguageSwitchControlFactory;
use App\Components\IMenuControlFactory;
use App\Components\ITextControlFactory;
use App\Components\JsControl;
use App\Components\LanguageSwitchControl;
use App\Components\MenuControl;
use App\Components\SlugCreatorControl;
use App\Components\TextControl;
use App\Services\AbstractLanguageService;
use App\Services\SiteSettingService;
use Kdyby\Translation\Translator;
use Nette\Application\ForbiddenRequestException;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\Template;

/**
 * Base presenter for all application presenters.
 * @property-read Template|\stdClass $template
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @author  Tomáš Surovčík <tomas.surovcik@q2.cz>
 * @author  Jan Hermann <jan.hermann@q2.cz>
 * @package Qetteweb
 */
abstract class AbstractQwPresenter extends Presenter
{
    /** @persistent perzistentni jazyk */
    public $locale;

    /** @var SiteSettingService @inject */
    public $siteSettings;

    /** @var Translator @inject */
    public $translator;

    /** @var CssControl @inject */
    public $cssControl;

    /** @var JsControl @inject */
    public $jsControl;

    /** @var SlugCreatorControl @inject */
    public $slugCreatorControl;

    /** @var GaCodeControl @inject */
    public $gaCodeControl;

    /** @var GtmCodeControl @inject */
    public $gtmCodeControl;

    /** @var ITextControlFactory @inject */
    public $textControlFactory;

    /** @var IMenuControlFactory @inject */
    public $menuControlFactory;

    /** @var ILanguageSwitchControlFactory @inject */
    public $languageSwitchControlFactory;

    /**
     * starter
     */
    protected function startup()
    {
        parent::startup();

        $this->template->siteSettings = $this->siteSettings;

        // prenos jazyka z perzistentniho parametru do sluzby
        $this->context->getByType(AbstractLanguageService::class)->setLang($this->locale);

        // nastaveni translatoru pro Latte
        $this->template->setTranslator($this->translator);

        // univerzalni filter
        $this->template->addFilter(null, 'LatteFilter::common');

        // extenze db routeru pro seo
        if ($this->context->parameters['router']['seo']) {
            $this->template->seoData = $this->context->getByType('DatabaseRouterSeoModel')->getSeo($this);
        }
    }
    
    /**
     * @param $element
     * @throws ForbiddenRequestException
     */
    public function checkRequirements($element)
    {
        parent::checkRequirements($element);
        $this->getUser()->getStorage()->setNamespace('app');
    }

    /**
     * @return CssControl
     */
    public function createComponentCssControl() {
        return $this->cssControl;
    }

    /**
     * @return JsControl
     */
    protected function createComponentJsControl() {
        return $this->jsControl;
    }

    /**
     * html text control (use {control text:title 'ident'})
     * @return TextControl
     */
    protected function createComponentText() {
        return $this->textControlFactory->create();
    }

    /**
     * menu control (use {control menu 'main'})
     * @return MenuControl
     */
    protected function createComponentMenu() {
        return $this->menuControlFactory->create();
    }

    /**
     * language switch control
     * @return LanguageSwitchControl
     */
    protected function createComponentLanguageSwitch() {
        return $this->languageSwitchControlFactory->create();
    }

    /**
     * slug generator control for filling database router
     * @return SlugCreatorControl
     */
    protected function createComponentSlugCreator() {
        return $this->slugCreatorControl;
    }

    /**
     * google analytics control
     * @return GaCodeControl
     */
    protected function createComponentGaCode() {
        $component = $this->gaCodeControl;
        $component->setLang($this->locale);
        return $component;
    }

    /**
     * google tag manager control
     * @return GtmCodeControl
     */
    protected function createComponentGoogleTagManager() {
        return $this->gtmCodeControl;
    }
}
