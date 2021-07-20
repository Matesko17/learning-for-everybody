<?php

namespace Test;

use Tester\Assert;
use Tester;
use Nette;

$container = require __DIR__ . '/bootstrap.php';

/**
 * Class LanguageServiceTest
 *
 * $ vendor/bin/tester tests/LanguageServiceTest.phpt
 * $ vendor/bin/tester -c tests/config/php.ini tests
 *
 * @todo need to be fixed
 * @skip
 * 
 * @package Test
 */
class LanguageServiceTest extends Tester\TestCase
{
    private $context = NULL;


    /**
     * defaultni konstruktor
     * @param Nette\DI\Container $container
     */
    public function __construct(Nette\DI\Container $container)
    {
        $this->context = $container;
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
     * samotne testy
     */


    public function testTypu()
    {
        Assert::true($this->context->getByType('AbstractLanguageService') instanceof \StaticLanguageService);
        Assert::equal('AbstractLanguageService', get_parent_class($this->context->getByType('StaticLanguageService')));
    }


    public function testDostupnostiJazyku()
    {
        $lang = $this->context->getByType('AbstractLanguageService');
        $lang->setLang('cs');

        Assert::equal(['cs' => 1, 'en' => 2], $lang->getAvailableLanguages());

        Assert::equal(['cs' => 'Čeština', 'en' => 'English'], $lang->getNameLanguages());

        Assert::equal('cs', $lang->getMainLang());
        Assert::equal(1, $lang->getIdMainLang());
    }


    public function testNastavovaniJazyku()
    {
        $lang = $this->context->getByType('AbstractLanguageService');

        $lang->setLang('cs');
        Assert::equal('cs', $lang->getLang());

        $lang->setLang('en');
        Assert::equal('en', $lang->getLang());

        $lang->setLang('es');
        Assert::equal('es', $lang->getLang());

        $lang->setLang('de');
        Assert::equal('de', $lang->getLang());

        $lang->setLang('sk');
        Assert::equal('sk', $lang->getLang());

        $lang->setLang('fr');
        Assert::equal('fr', $lang->getLang());
    }


    public function testNacitaniKoduJazyku()
    {
        $lang = $this->context->getByType('AbstractLanguageService');

        $lang->setLang('cs');
        Assert::equal('cs', $lang->getCode());
        Assert::equal(1, $lang->getId());

        $lang->setLang('en');
        Assert::equal('en', $lang->getCode());
        Assert::equal(2, $lang->getId());

        $lang->setLang('es');
        Assert::equal('cs', $lang->getCode());
        Assert::equal(1, $lang->getId());

        $lang->setLang('de');
        Assert::equal('en', $lang->getCode());
        Assert::equal(2, $lang->getId());

        $lang->setLang('sk');
        Assert::equal('cs', $lang->getCode());
        Assert::equal(1, $lang->getId());

        $lang->setLang('fr');
        Assert::equal('en', $lang->getCode());
        Assert::equal(2, $lang->getId());
    }


    public function testExistenceJazyku()
    {
        $lang = $this->context->getByType('AbstractLanguageService');

        $lang->setLang('cs');
        Assert::equal('cs', $lang->getCode());
        Assert::equal(1, $lang->getId());

        Assert::true($lang->isExists('cs'));
        Assert::true($lang->isExists('en'));

        Assert::true($lang->isExists('sk'));
        Assert::true($lang->isExists('de'));
        Assert::true($lang->isExists('fr'));

        Assert::false($lang->isExists('es'));
    }


    public function testNacteniExistenceJazyku()
    {
        $lang = $this->context->getByType('AbstractLanguageService');

        $lang->setLang('cs');
        Assert::equal('cs', $lang->getCode());
        Assert::equal(1, $lang->getId());

        Assert::equal(1, $lang->getExistId('cs'));
        Assert::equal(2, $lang->getExistId('en'));

        Assert::equal(1, $lang->getExistId('sk'));
        Assert::equal(2, $lang->getExistId('de'));
        Assert::equal(2, $lang->getExistId('fr'));

        Assert::equal(1, $lang->getExistId('es'));
    }
}

//Spusteni testovacich metod
$testCase = new LanguageServiceTest($container);
$testCase->run();
