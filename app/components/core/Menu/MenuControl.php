<?php

namespace App\Components;

use App\Model\Facades\MenuFacade;
use Kdyby\Translation\Translator;
use Nette\Application\UI\Control;


/**
 * Class MenuControl
 *
 * @author  Jakub Markus <jakub.markus@q2.cz>
 * @package Qetteweb
 */
class MenuControl extends Control
{
    /** @var Translator */
    private $translator;
    
    /** @var string */
    private $pathTemplate;
    
    /** @var MenuFacade */
    private $menuFacade;
    
    /**
     * @param MenuFacade $menuFacade
     * @param Translator $translator
     */
    public function __construct(MenuFacade $menuFacade, Translator $translator)
    {
        parent::__construct();
        $this->menuFacade = $menuFacade;
        $this->translator = $translator;
        $this->pathTemplate = __DIR__.'/Menu.latte';
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
     * @param string|null $ident
     * @param string $lang
     * @param string $class
     */
    public function render($ident = null, $lang = "", $class = "")
    {
        $this->template->setTranslator($this->translator);
        $this->template->setFile($this->pathTemplate);
        $this->template->class = $class;
        if(isset($ident)) {
            $this->template->menu = $this->menuFacade->getMenuByIdent($ident, $lang);
        }
        $this->template->render();
    }
}
