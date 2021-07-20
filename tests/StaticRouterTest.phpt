<?php

namespace Test;

use Tester\Assert;
use Tester;
use Nette;

$container = require __DIR__ . '/bootstrap.php';

/**
 * Class StaticRouterTest
 *
 * $ vendor/bin/tester tests/StaticRouterTest.phpt
 * $ vendor/bin/tester -c tests/config/php.ini tests
 *
 * @todo need to be fixed
 * @skip
 * 
 * @package Test
 */
class StaticRouterTest extends Tester\TestCase
{
    private $context, $router = NULL;


    /**
     * defaultni konstruktor
     * @param Nette\DI\Container $container
     */
    public function __construct(Nette\DI\Container $container)
    {
        $this->context = $container;
        $this->router = new \StaticRouter($this->context, $this->context->parameters['staticRouter']);
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
     * test rozkladu adresy
     */


    public function testMatch1()
    {
        $expected = $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/staticky-slug')));
        Assert::true($expected instanceof Nette\Application\Request);

        $match = $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/staticky-slug')));
        Assert::true($match instanceof Nette\Application\Request);

        Assert::equal($expected, $match);
    }


    public function testMatch2()
    {
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'pokus2'], [], [], ['secured' => false]),
            $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/staticky-slug1')))
        );
    }


    public function testMatch3()
    {
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['lang' => 'en', 'action' => 'pokus'], [], [], ['secured' => false]),
            $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/en/static-slu')))
        );
    }


    public function testMatch4()
    {
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['lang' => 'en', 'action' => 'pokus2'], [], [], ['secured' => false]),
            $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/en/static-slug1')))
        );
    }


    public function testMatch5()
    {
        Assert::null($this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb'))));
    }


    public function testMatch6()
    {
        Assert::null($this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/en'))));
    }


    /**
     * test na ajax
     */
    public function testMatch7()
    {
        $router = new \StaticRouter($this->context, $this->context->parameters['staticRouter'], ['presenter' => 'Homepage', 'action' => 'default', 'lang' => 'cs']);    // vytvareni instance jako v RouterFactory
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['action' => 'default', 'lang' => 'cs', 'do' => 'LiveSearch', 'q' => 'alb'], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/?do=LiveSearch&q=alb')))
        );
    }


    /**
     * test na nadbytecne parametry 1
     */
    public function testMatch8()
    {
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['action' => 'pokus', 'lang' => 'cs', 'huh' => 'ccc'], [], [], ['secured' => false]),
            $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/staticky-slug?huh=ccc')))
        );
    }


    /**
     * test konstrukce adresy
     */


    public function testConstructUrl1()
    {
        Assert::equal(
            'http://NetteWeb/staticky-slug',
            $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'pokus'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );
    }


    public function testConstructUrl2()
    {
        Assert::equal(
            'http://NetteWeb/staticky-slug1',
            $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'pokus2'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );
    }


    public function testConstructUrl3()
    {
        Assert::equal(
            'http://NetteWeb/en/static-slu',
            $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'en', 'action' => 'pokus'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );
    }


    public function testConstructUrl4()
    {
        Assert::equal(
            'http://NetteWeb/en/static-slug1',
            $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'en', 'action' => 'pokus2'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );
    }


    public function testConstructUrl5()
    {
        Assert::null($this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'abc'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb')));
    }


    public function testConstructUrl6()
    {
        Assert::equal(
            'http://NetteWeb/staticky-slug?huhu=bbbb',
            $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'pokus', 'huhu' => 'bbbb'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );
    }


    /**
     * ostatni testy
     */


    public function testMetadata1()
    {
        // spravne smerovani pri prazdne adrese
        $router = new \StaticRouter($this->context, $this->context->parameters['staticRouter'], []);

        Assert::null($router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb'))));
    }


    public function testMetadata2()
    {
        // spravne smerovani pri prazdne adrese
        $router = new \StaticRouter($this->context, $this->context->parameters['staticRouter'], ['presenter' => 'Homepage']);

        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', [], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb')))
        );
    }


    public function testMetadata3()
    {
        // spravne smerovani pri prazdne adrese
        $router = new \StaticRouter($this->context, $this->context->parameters['staticRouter'], ['presenter' => 'NewsPage']);

        Assert::equal(
            new Nette\Application\Request('NewsPage', 'GET', [], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb')))
        );
    }


    public function testMetadata4()
    {
        // spravne smerovani pri prazdne adrese
        $router = new \StaticRouter($this->context, $this->context->parameters['staticRouter'], ['presenter' => 'NewsPage', 'lang' => 'cs']);

        Assert::equal(
            new Nette\Application\Request('NewsPage', 'GET', ['lang' => 'cs'], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb')))
        );
    }


    public function testMetadata5()
    {
        // spravne smerovani pri prazdne adrese
        $router = new \StaticRouter($this->context, $this->context->parameters['staticRouter'], ['presenter' => 'NewsPage', 'lang' => 'de']);

        Assert::equal(
            new Nette\Application\Request('NewsPage', 'GET', ['lang' => 'de'], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb')))
        );
    }


    public function testMetadata6()
    {
        // spravne smerovani pri prazdne adrese
        $router = new \StaticRouter($this->context, $this->context->parameters['staticRouter'], ['presenter' => 'NewsPage', 'lang' => 'en', 'action' => 'default']);

        Assert::equal(
            new Nette\Application\Request('NewsPage', 'GET', ['lang' => 'en', 'action' => 'default'], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb')))
        );
    }


    public function testMetadata7()
    {
        // spravne smerovani pri prazdne adrese
        $router = new \StaticRouter($this->context, $this->context->parameters['staticRouter'], ['presenter' => 'NewsPage', 'lang' => 'en', 'action' => 'detail']);

        Assert::equal(
            new Nette\Application\Request('NewsPage', 'GET', ['lang' => 'en', 'action' => 'detail'], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb')))
        );
    }


    public function testOneWay()
    {
        $router = new \StaticRouter($this->context, $this->context->parameters['staticRouter'], [], \StaticRouter::ONE_WAY);
        Assert::null(
            $router->constructUrl(
                new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'pokus'], [], [], ['secured' => false]),
                new Nette\Http\UrlScript('http://NetteWeb/staticky-slug'))
        );
    }


    public function testHttps()
    {
        $router = new \StaticRouter($this->context, $this->context->parameters['staticRouter'], [], \StaticRouter::SECURED);
        Assert::equal(
            'https://NetteWeb/staticky-slug',
            $router->constructUrl(
                new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'pokus'], [], [], ['secured' => true]),
                new Nette\Http\UrlScript('http://NetteWeb'))
        );
    }
}

//Spusteni testovacich metod
$testCase = new StaticRouterTest($container);
$testCase->run();
