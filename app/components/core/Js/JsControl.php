<?php

namespace App\Components;

use Nette\Application\UI\Control;
use Nette\FileNotFoundException;
use Nette\InvalidArgumentException;
use Kdyby\Translation\Translator;

/**
 * Class JsControl
 * 
 * @author  Jakub Markus <jakub.markus@q2.cz>
 * @package Qetteweb
 */
class JsControl extends Control
{
    const PATH_EXTENSION = 'js';

    /** @var string */
    private $wwwDir;

    /** @var array */
    private $data;

    /** @var string */
    private $templatePath;

    /** @var bool */
    private $isProduction;

    /** @var array */
    private $templateData = array();

    /** @var Translator */
    private $translator;

    /**
     * JsControl constructor.
     *
     * @param string $wwwDir
     * @param array  $data
     * @param string $environment
     * @param Translator $translator
     */
    public function __construct($wwwDir, array $data, $environment, Translator $translator)
    {
        parent::__construct();

        $this->wwwDir = $wwwDir;
        $this->data = $data;
        $this->translator = $translator;

        $this->isProduction = false;
        if ($environment === 'production') {
            $this->isProduction = true;
        }

        $this->templatePath = __DIR__ . '/js.latte';
    }

    /**
     * @param $source
     * @throws InvalidArgumentException|FileNotFoundException
     */
    public function render($source)
    {
        if (!array_key_exists($source, $this->data)) {
            throw new InvalidArgumentException('$source "' . $source . '" ' . $this->translator->translate('components.jsControl.notExists') . '.');
        }

        if (!array_key_exists('files', $this->data[$source])) {
            throw new InvalidArgumentException($this->data[$source] . ' - ' . $this->translator->translate('components.jsControl.missingFilesConfig') . '.');
        }

        if (!is_array($this->data[$source]['files'])) {
            throw new InvalidArgumentException($this->data[$source] . ' - ' . $this->translator->translate('components.jsControl.filesIsNotArray') . '.');
        }

        foreach ($this->data[$source]['files'] as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === self::PATH_EXTENSION) {
                $filePath = $this->wwwDir . '/' . $file;
                if (file_exists($filePath)) {
                    $this->addToTemplateData($filePath, $file);

                } else {
                    $this->sendFileNotFoundException($file);
                }
            } else {
                $extension = $this->isProduction ? '.min.' . self::PATH_EXTENSION : '.' . self::PATH_EXTENSION;
                $filePath = $this->wwwDir . '/' . $file . $extension;

                $extension2 = !$this->isProduction ? '.min.' . self::PATH_EXTENSION : '.' . self::PATH_EXTENSION;
                $filePath2 = $this->wwwDir . '/' . $file . $extension;

                if (file_exists($filePath)) {
                    $this->addToTemplateData($filePath, $file . $extension);
                } elseif (file_exists($filePath2)) {
                    $this->addToTemplateData($filePath2, $file . $extension2);
                } else {
                    $this->sendFileNotFoundException($file);
                }
            }
        }

        $this->template->data = $this->templateData;
        $this->template->setFile($this->templatePath);
        $this->template->render();
    }

    /**
     * @param string $filePath
     * @param string $file
     */
    private function addToTemplateData($filePath, $file)
    {
        $version = filemtime($filePath);
        $this->templateData[] = $file . '?v=' . $version;
    }

    /**
     * @param $file
     * @throws FileNotFoundException
     */
    private function sendFileNotFoundException($file)
    {
        if (!$this->isProduction) {
            throw new FileNotFoundException('File ' . $file . ' not found.');
        }
    }
}