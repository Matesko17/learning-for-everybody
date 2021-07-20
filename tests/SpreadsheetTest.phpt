<?php

namespace Tests;

use App\Model\Entities\Translation;
use App\Model\Facades\TranslationFacade;
use App\Services\SpreadsheetService;
use Nette\DI\Container;
use Nette\FileNotFoundException;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use Tester\Assert;
use Tester\TestCase;

$container = require __DIR__."/bootstrap.php";

/**
 * Class SpreadsheetTest
 *
 * @testCase
 *
 * @author Jan Hermann <jan.hermann@q2.cz>
 * @package Qetteweb
 */
class SpreadsheetTest extends TestCase
{
    private $context;
    
    /** @var SpreadsheetService */
    private $spreadsheetService;
    
    public function __construct(Container $container)
    {
        $this->context = $container;
    }
    
    public function testCsv()
    {
        $spreadsheetService = new SpreadsheetService(__DIR__."/spreadsheets/translation.csv");
        $data = $spreadsheetService->parse();
        
        Assert::equal("Hello world!!!", $data[0]["translate"]);
    }
    
    public function testEntity()
    {
        $this->spreadsheetService = new SpreadsheetService(__DIR__."/spreadsheets/translation.csv");
        $data = $this->spreadsheetService->parse();
        
        Assert::equal("Hello world!!!", $data[0]["translate"]);
        
        /** @var TranslationFacade $translationFacade */
        $translationFacade = $this->context->getService("translationFacade");
        $entity = $translationFacade->hasEntityFrom($data[0]);
        Assert::type(Translation::class, $entity);
    }
    
    public function testFileNotFound()
    {
        Assert::exception(function() {
            $spreadsheetService = new SpreadsheetService(__DIR__."/spreadsheets/translation.cs");
        }, FileNotFoundException::class);
    }
    
    public function testNotEntity()
    {
        $spreadsheetService = new SpreadsheetService(__DIR__."/spreadsheets/translation-fail.csv");
        $data = $spreadsheetService->parse();
        
        Assert::equal("Hello world!!!", $data[0]["translate"]);
        
        /** @var TranslationFacade $translationFacade */
        $translationFacade = $this->context->getService("translationFacade");
        $entity = $translationFacade->hasEntityFrom($data[0]);
        Assert::false($entity);
    }
    
    public function testTxt()
    {
        Assert::exception(function() {
            $spreadsheetService = new SpreadsheetService(__DIR__."/spreadsheets/translation.txt");
        }, Exception::class);
    }
    
    public function testXlsx()
    {
        $spreadsheetService = new SpreadsheetService(__DIR__."/spreadsheets/translation.xlsx");
        $data = $spreadsheetService->parse();
        
        Assert::equal("Hello world!!!", $data[0]["translate"]);
    }
}

$testCase = new SpreadsheetTest($container);
$testCase->run();
