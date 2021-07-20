<?php

namespace App\Components;

use Nette\Application\UI\Control;
use Nette\Http\Request;
use Kdyby\Translation\Translator;
use App\Services\AbstractLanguageService;


/**
 * Class LanguageSwitchControl.
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @author  Tomáš Surovčík <tomas.surovcik@q2.cz>
 * @package Qetteweb
 */
class LanguageSwitchControl extends Control
{
    /** @var AbstractLanguageService */
    private $language;

    /** @var Translator */
    private $translator;

    /** @var Request */
    private $request;

    /** @var string */
    private $pathTemplate;

    /** @var boolean domain switch for languages */
    private $languageDomainSwitch;

    /** @var array domain for languages */
    private $languageDomainAlias;


    /**
     * @param AbstractLanguageService $language
     * @param Translator $translator
     * @param Request $request
     */
    public function __construct(
        $languageDomainSwitch,
        $languageDomainAlias,
        AbstractLanguageService $language,
        Translator $translator,
        Request $request
    ) {
        parent::__construct();
        $this->language = $language;
        $this->translator = $translator;
        $this->languageDomainSwitch = $languageDomainSwitch;
        $this->languageDomainAlias = $languageDomainAlias;
        $this->request = $request;
        $this->pathTemplate = __DIR__ . '/LanguageSwitch.latte';
    }


    /**
     * Setting template path.
     * @param $path
     * @return $this
     */
    public function setPathTemplate($path)
    {
        $this->pathTemplate = $path;
        return $this;
    }


    /**
     * Component render method.
     */
    public function render()
    {
        $template = $this->getTemplate();

        $template->addFilter(null, 'LatteFilter::common');
        $template->setTranslator($this->translator);
        $template->setFile($this->pathTemplate);

        $template->domain = null;
        if ($this->languageDomainSwitch) {
            $template->flipLanguageDomainAlias = array_flip($this->languageDomainAlias);
            // loading host url
            $host = $this->request->url->host;
            $pos = strrpos($host, '.');
            $template->domain = substr($host, 0, $pos + 1);
        }

        $template->languages = $this->language->getNameLanguages();
        $template->languageCode = $this->language->getCode();

        $template->render();
    }
}
