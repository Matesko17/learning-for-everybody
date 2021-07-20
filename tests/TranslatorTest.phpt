<?php

namespace Tests;

use App\Services\AbstractTranslator;
use App\Services\DatabaseTranslator;
use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

$container = require __DIR__.'/bootstrap.php';

/**
 * Class TranslatorTest
 *
 * $ vendor/bin/tester -c tests/config/php.ini -i
 * $ vendor/bin/tester -c tests/config/php.ini tests/TranslatorTest.phpt
 * $ vendor/bin/tester -c tests/config/php.ini tests
 * 
 * @todo need to be fixed
 * @skip
 *
 * @package Test
 */
class TranslatorTest extends TestCase
{
    private $context, $translator = null;
    
    /**
     * defaultni konstruktor
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->context = $container;

//        $this->context->getByType('AbstractLanguageService');
        $this->translator = $this->context->getByType('App\Services\AbstractLanguageService');
    }
    
    public function setUp()
    {
        // priprava
    }
    
    public function tearDown()
    {
        // uklid
    }
    
    
    /**
     * test prekladu
     */
    
    
    
    public function testTranslate1()
    {
        var_dump($this->translator);
        Assert::true($this->translator instanceof AbstractTranslator);
        Assert::true($this->translator instanceof DatabaseTranslator);
        
        Assert::same('ahoj svete!', $this->translator->translate('ahoj svete!'));
    }
    
    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testTranslate2()
    {
        $this->translator->translate('mame doma %d okno', 0);   // vytvori vsechny pluraly zaraz
        Assert::same('mame doma 0 oken', $this->translator->translate('mame doma %d okno', 0));
        Assert::same('mame doma 1 okno', $this->translator->translate('mame doma %d okno', 1));
        Assert::same('mame doma 2 okna', $this->translator->translate('mame doma %d okno', 2));
        Assert::same('mame doma 5 oken', $this->translator->translate('mame doma %d okno', 5));
    }
    
    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testTranslate3()
    {
        Assert::same('mame doma 0 oken a 1 tapetu', $this->translator->translate('mame doma %d okno a %d tapetu', [0, 1]));
        Assert::same('mame doma 1 okno a 1 tapetu', $this->translator->translate('mame doma %d okno a %d tapetu', [1, 1]));
        Assert::same('mame doma 2 okna a 1 tapetu', $this->translator->translate('mame doma %d okno a %d tapetu', [2, 1]));
        Assert::same('mame doma 5 oken a 1 tapetu', $this->translator->translate('mame doma %d okno a %d tapetu', [5, 1]));
    }
}

//Spusteni testovacich metod
$testCase = new TranslatorTest($container);
$testCase->run();
