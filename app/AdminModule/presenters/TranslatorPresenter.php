<?php

namespace App\AdminModule\Presenters;

use App\Model\Facades\TranslationFacade;
use App\Services\SpreadsheetService;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Doctrine\ORM\ORMException;
use Kdyby\Translation\Translator;
use PhpOffice\PhpSpreadsheet\Exception as PSException;
use Tracy\Debugger;

use App\AdminModule\Services\TranslationService;

/**
 * Class TranslatorPresenter.
 * 
 * @author Kamil Walig <kamil.walig@q2.cz>
 * @author Jan Hermann <jan.hermann@q2.cz>
 * @package Qetteweb
 */
class TranslatorPresenter extends AdminBasePresenter
{
    /** @var TranslationService @inject */
    public $translationService;

    /** @var Translator @inject */
    public $translator;
    
    /** @var TranslationFacade @inject */
    public $translationFacade;


    public function renderCreate()
    {
        try {
            $this->translationService->create();
            $this->flashMessage($this->translator->translate("admin.translator.create.success"), "success");
        } catch(ORMException $e) {
            Debugger::log($e->getMessage(), "translator");
            $this->flashMessage($this->translator->translate("admin.translator.create.fail"), "danger");
        }
    }

    public function renderGenerate()
    {
        try {
            $this->translationService->generate();
            $this->flashMessage($this->translator->translate("admin.translator.generate.success"), "success");
        } catch(Exception $e) {
            Debugger::log($e->getMessage(), "translator");
            $this->flashMessage($this->translator->translate("admin.translator.generate.fail"), "danger");
        }
    }

    public function renderRegenerate()
    {
        try {
            $this->translationService->create();
            $this->flashMessage($this->translator->translate("admin.translator.create.success"), "success");
        } catch(ORMException $e) {
            Debugger::log($e->getMessage(), "translator");
            $this->flashMessage($this->translator->translate("admin.translator.create.fail"), "danger");
        }

        try {
            $this->translationService->generate();
            $this->flashMessage($this->translator->translate("admin.translator.generate.success"), "success");
        } catch(Exception $e) {
            Debugger::log($e->getMessage(), "translator");
            $this->flashMessage($this->translator->translate("admin.translator.generate.fail"), "danger");
        }
    }
    
    public function renderImport($file) {
        $filesPath = $this->context->getParameters()["files"]["path"];
        try {
            $this->translationService->import($filesPath.$file);
            $this->flashMessage($this->translator->translate("admin.translator.import.success"), "success");
        } catch(PSException|OptimisticLockException|ORMException $e) {
            Debugger::log($e->getMessage(), "translator");
            $this->flashMessage($e->getMessage(), "danger");
        }
    }
}
