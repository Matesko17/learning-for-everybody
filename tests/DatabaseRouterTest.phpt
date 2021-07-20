<?php

namespace Test;

use Tester\Assert;
use Tester;
use Nette;

$container = require __DIR__ . '/bootstrap.php';

/**
 * Class DatabaseRouterTest
 *
 * $ vendor/bin/tester -c tests/config/php.ini -i
 * $ vendor/bin/tester -c tests/config/php.ini tests/DatabaseRouterTest.phpt
 * $ vendor/bin/tester -c tests/config/php.ini tests
 * $ vendor/bin/tester -c tests/config/php.ini -w tests
 *
 * @todo need to be fixed
 * @skip
 * 
 * @package Test
 */
class DatabaseRouterTest extends Tester\TestCase
{
    private $context = NULL;
    private $router = NULL, $routerModel = NULL;
    /** @var \Dibi\Connection */
    private $database = NULL;
    private $tableRoute = 'prefix_route', $tableRouteAlias = 'prefix_route_alias', $tableRouteSeo = 'prefix_route_seo';


    /**
     * defaultni konstruktor
     * @param Nette\DI\Container $container
     */
    public function __construct(Nette\DI\Container $container)
    {
        $this->context = $container;
        $this->router = new \DatabaseRouter($container);    // vytvareni instance jako v RouterFactory

        $this->routerModel = $this->context->getByType('DatabaseRouterModel');  // vytahovani service

        $this->database = $this->context->getService('dibi.connection');

        $this->database->query('truncate %n', $this->tableRoute);
        $this->database->query('truncate %n', $this->tableRouteAlias);
//        $this->database->query('truncate %n', $this->tableRouteSeo);
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
     * test ukladani do databaze
     */


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testInsertSlug1()
    {
        $presenter = new MiniPresenter('Homepage', 'detail', ['lang' => 'cs', 'id' => 123]);

        // test vlozeni slugu
        $this->routerModel->insertSlug($presenter, 'home/detailik/detail abc');

        // test obsahu slugu
        $c = $this->database->select('Id, Presenter, Action')
            ->from($this->tableRoute)
            ->where('Presenter=%s', 'Homepage')
            ->where('Action=%s', 'detail')
            ->fetch();

        Assert::true(is_numeric($c->Id));
        Assert::equal(1, $c->Id);
        Assert::equal('Homepage', $c->Presenter);
        Assert::equal('detail', $c->Action);

        $parameters = ['action' => 'detail', 'id' => 123, 'lang' => 'cs',];

        $c1 = $this->database->select('a.Id, Presenter, Action, IdRoute, Language, Slug, IdItem, Parameters, Added')
            ->from($this->tableRouteAlias)->as('a')
            ->join($this->tableRoute)->as('r')->on('r.Id=a.IdRoute')
            ->where('IdRoute=%i', $c->Id)
            ->where('Action=%s', 'detail')
            ->where('IdItem=%i', 123)
            ->where('Language=%s', 'cs')
            ->fetch();

        Assert::true(is_numeric($c1->Id));
        Assert::equal(1, $c1->Id);
        Assert::same($c->Id, $c1->IdRoute);
        Assert::equal('cs', $c1->Language); //$this->routerModel->getLanguageService()->getCode()
        Assert::equal('home/detailik/detail-abc', $c1->Slug);
        Assert::equal(123, $c1->IdItem);
        //Assert::true(DateTime instanceof $c1->Added);

        $actual = $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detailik/detail-abc')));
        Assert::true($actual instanceof Nette\Application\Request);
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', $parameters, [], [], ['secured' => false]),
            $actual
        );
    }


    /**
     * test rozkladu adresy
     */


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testMatch1()
    {
        $Parameters = ['action' => 'detail', 'id' => 123, 'lang' => 'cs',];
        $actual = $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detailik/detail-abc')));
        Assert::true($actual instanceof Nette\Application\Request);
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', $Parameters, [], [], ['secured' => false]),
            $actual
        );

        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', $Parameters, [], [], ['secured' => false]),
            $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/cs/home/detailik/detail-abc')))
        );
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testMatch2()
    {
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['action' => 'detail', 'id' => 123, 'lang' => 'cs', 'vp' => '22'], [], [], ['secured' => false]),
            $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detailik/detail-abc_22')))
        );
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testMatch3()
    {
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['action' => 'detail', 'id' => 123, 'lang' => 'cs', 'vp' => '9'], [], [], ['secured' => false]),
            $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detailik/detail-abc_9')))
        );
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testMatch4()
    {
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['action' => 'detail', 'id' => 123, 'lang' => 'cs', 'vp' => '0'], [], [], ['secured' => false]),
            $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detailik/detail-abc_0')))
        );
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testMatch5()
    {
        Assert::null($this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detailik/detail-abc_'))));
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testMatch6()
    {
        Assert::null($this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/neco-jineho'))));
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testMatch7()
    {
        Assert::null($this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/'))));
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testMatch8()
    {
        Assert::null($this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/ahoj_1'))));
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testMatch9()
    {
        // vyhodnoti anglictinu a prepne jazykvou sluzbu na anglictinu
        Assert::null($this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/en/home/detailik/detail-abc_22'))));

        $this->routerModel->getLanguageService()->setLang('cs');    // vraceni jazyka na CS
    }


    /**
     * test na ajax
     * @dataProvider config/databases.ini mysql
     */
    public function testMatch10()
    {
        $router = new \DatabaseRouter($this->context, null, ['presenter' => 'Homepage', 'action' => 'default', 'lang' => 'cs']);    // vytvareni instance jako v RouterFactory
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['action' => 'default', 'lang' => 'cs', 'do' => 'LiveSearch', 'q' => 'alb'], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/?do=LiveSearch&q=alb')))
        );
    }


    /**
     * test na nadbytecne parametry 1
     * @dataProvider config/databases.ini mysql
     */
    public function testMatch11()
    {
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['action' => 'detail', 'id' => 123, 'lang' => 'cs', 'huh' => 'ccc'], [], [], ['secured' => false]),
            $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detailik/detail-abc?huh=ccc')))
        );
    }


    /**
     * test na nadbytecne parametry 2
     * @dataProvider config/databases.ini mysql
     */
    public function testMatch12()
    {
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['action' => 'detail', 'id' => 123, 'lang' => 'cs', 'vp' => '46', 'huha' => 'ddd'], [], [], ['secured' => false]),
            $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detailik/detail-abc_46?huha=ddd')))
        );
    }


    /**
     * test strankovani na HP
     * @dataProvider config/databases.ini mysql
     */
    public function testMatch13()
    {
        // je prazdny slug
        Assert::null($this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/_223'))));
        Assert::null($this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/?vp=223'))));

        $router = new \DatabaseRouter($this->context, null, ['presenter' => 'Homepage', 'action' => 'default', 'lang' => 'cs']);    // vytvareni instance jako v RouterFactory
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['action' => 'default', 'lang' => 'cs', 'vp' => '224'], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/?vp=224')))
        );
        // Assert::null($router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/?vp=224'))));
    }


    /**
     * test konstrukce adresy
     */


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testConstructUrl1()
    {
        Assert::equal(
            'http://NetteWeb/home/detailik/detail-abc',
            $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'detail', 'id' => 123], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testConstructUrl2()
    {
        Assert::equal(
            'http://NetteWeb/home/detailik/detail-abc_0',
            $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'detail', 'id' => 123, 'vp' => '0'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testConstructUrl3()
    {
        Assert::equal(
            'http://NetteWeb/home/detailik/detail-abc_22',
            $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'detail', 'id' => 123, 'vp' => '22'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testConstructUrl4()
    {
        Assert::equal(
            'http://NetteWeb/home/detailik/detail-abc',
            $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'detail', 'id' => 123, 'vp' => NULL], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testConstructUrl5()
    {
        Assert::equal(
            'http://NetteWeb/home/detailik/detail-abc?huhu=bbbb',
            $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'detail', 'id' => 123, 'vp' => NULL, 'huhu' => 'bbbb'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );
    }


    /**
     * test prelozitelnych linku pro zadany jazyk
     * @dataProvider config/databases.ini mysql
     */
    public function testConstructUrl6()
    {
        $presenter1 = new MiniPresenter('Homepage', 'def', ['lang' => 'cs',]);
        $presenter2 = new MiniPresenter('Homepage', 'def', ['lang' => 'en',]);

        // test vlozeni slugu
        $this->routerModel->insertSlug($presenter1, 'home/detail-cs');
        $this->routerModel->insertSlug($presenter2, 'home/detail-en');

        // slozeni cs adresy
        Assert::equal(
            'http://NetteWeb/home/detail-cs',
            $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'def'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );

        // test na systemovy jazyk (defaultne = 1)
        Assert::equal(1, $this->routerModel->getLanguageService()->getId());

        // slozeni adresy pri nezadanem jazyku
        Assert::equal(
            'http://NetteWeb/home/detail-cs',
            $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['action' => 'def'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );

        // slozeni adresy pri chybnem jazyku
        Assert::null(
            $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'es', 'action' => 'def'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );

        // slozeni en adresy
        Assert::equal(
            'http://NetteWeb/en/home/detail-en',
            $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'en', 'action' => 'def'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );
    }


    /**
     * ostatni testy
     */


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testMetadata1()
    {
        // spravne smerovani pri prazdne adrese
        $router = new \DatabaseRouter($this->context, NULL);

        Assert::null($router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb'))));
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testMetadata2()
    {
        // spravne smerovani pri prazdne adrese
        $router = new \DatabaseRouter($this->context, NULL, ['presenter' => 'Homepage']);

        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', [], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb')))
        );
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testMetadata3()
    {
        // spravne smerovani pri prazdne adrese
        $router = new \DatabaseRouter($this->context, NULL, ['presenter' => 'NewsPage']);

        Assert::equal(
            new Nette\Application\Request('NewsPage', 'GET', [], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb')))
        );
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testMetadata4()
    {
        // spravne smerovani pri prazdne adrese
        $router = new \DatabaseRouter($this->context, NULL, ['presenter' => 'NewsPage', 'lang' => 'cs']);

        Assert::equal(
            new Nette\Application\Request('NewsPage', 'GET', ['lang' => 'cs'], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb')))
        );
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testMetadata5()
    {
        // spravne smerovani pri prazdne adrese
        $router = new \DatabaseRouter($this->context, NULL, ['presenter' => 'NewsPage', 'lang' => 'es']);

        Assert::equal(
            new Nette\Application\Request('NewsPage', 'GET', ['lang' => 'es'], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb')))
        );
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testMetadata6()
    {
        // spravne smerovani pri prazdne adrese
        $router = new \DatabaseRouter($this->context, NULL, ['presenter' => 'NewsPage', 'lang' => 'es', 'action' => 'hokus']);

        Assert::equal(
            new Nette\Application\Request('NewsPage', 'GET', ['lang' => 'es', 'action' => 'hokus'], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb')))
        );
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testOneWay()
    {
        $router = new \DatabaseRouter($this->context, NULL, [], \DatabaseRouter::ONE_WAY);
        Assert::null(
            $router->constructUrl(
                new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default'], [], [], ['secured' => false]),
                new Nette\Http\UrlScript('http://NetteWeb/home/detailik/detail-abc'))
        );//                dump($c, $parameters);
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testHttps()
    {
        $router = new \DatabaseRouter($this->context, NULL, [], \DatabaseRouter::SECURED);
        Assert::equal(
            'https://NetteWeb/home/detailik/detail-abc',
            $router->constructUrl(
                new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'detail', 'id' => 123], [], [], ['secured' => true]),
                new Nette\Http\UrlScript('http://NetteWeb'))
        );
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testMatchAlias()
    {
        $presenter = new MiniPresenter('Homepage', 'alias', ['lang' => 'cs',]);

        // test vlozeni slugu
        sleep(1);   // prodleva kvuli casovemu razitku
        $this->routerModel->insertSlug($presenter, 'home/detail/detail');
        sleep(1);   // prodleva kvuli casovemu razitku
        $this->routerModel->insertSlug($presenter, 'home/detailovy/parametr');

        // puvodni - prvni
//        Assert::equal(
//            new Nette\Application\Request('Homepage', 'GET', ['action' => 'detail', 'id' => 123, 'lang' => 'cs'], [], [], ['secured' => false]),
//            $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detailik/detail-abc')))
//        );

        // varianta 1
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['action' => 'alias', 'lang' => 'cs'], [], [], ['secured' => false]),
            $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detail/detail')))
        );

        // varianta 2 - posledni
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['action' => 'alias', 'lang' => 'cs'], [], [], ['secured' => false]),
            $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detailovy/parametr')))
        );

        // slozena adresa obsahu posledni adresu
        Assert::equal(
            'http://NetteWeb/home/detailovy/parametr',
            $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'alias'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testMetadataParameters()
    {
        // spravne smerovani pri prazdne adrese
        $router = new \DatabaseRouter($this->context, ['gg', 'bb']);

        // puvodni
        $presenter = new MiniPresenter('Homepage', 'default', ['lang' => 'cs', 'gg' => '123']);
        $this->routerModel->insertSlug($presenter, 'home/parametr');

        // zmena 1
        sleep(1);   // prodleva kvuli casovemu razitku
        $presenter = new MiniPresenter('Homepage', 'default', ['lang' => 'cs', 'bb' => '321']);
        $this->routerModel->insertSlug($presenter, 'home/parametr1');

        // zmena 2
        sleep(1);   // prodleva kvuli casovemu razitku
        $presenter = new MiniPresenter('Homepage', 'default', ['lang' => 'cs', 'gg' => '456', 'bb' => '789']);
        $this->routerModel->insertSlug($presenter, 'home/parametr2');

        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'gg' => '123'], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/parametr')))
        );

        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'bb' => '321'], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/parametr1')))
        );

        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'gg' => '456', 'bb' => '789'], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/parametr2')))
        );

        // vice parametru navic
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'gg' => '456', 'bb' => '789', 'hu' => 'aaa'], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/parametr2?hu=aaa')))
        );

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // zpetna kontrola na slozeni (puvodni)
        Assert::equal(
            'http://NetteWeb/home/parametr2',
            $router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'gg' => '123'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );

        // zpetna kontrola na slozeni (zmena 1)
        Assert::equal(
            'http://NetteWeb/home/parametr2',
            $router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'bb' => '321'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );

        // zpetna kontrola na slozeni (zmena 2)
        Assert::equal(
            'http://NetteWeb/home/parametr2',
            $router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'gg' => '456', 'bb' => '789'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );

        // zpetna kontrola na slozeni (zmena 2)
        Assert::equal(
            'http://NetteWeb/home/parametr2?hu=aaa',
            $router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'gg' => '456', 'bb' => '789', 'hu' => 'aaa'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
        );
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testLanguageDomainSwitch1()
    {
        $this->context->parameters['router']['languageDomainSwitch'] = true;
        $router = new \DatabaseRouter($this->context);

        $presenter = new MiniPresenter('News', 'detail', ['lang' => 'cs', 'id' => 1]);
        $this->routerModel->insertSlug($presenter, 'novinky/detail-1');

        $presenter = new MiniPresenter('News', 'detail', ['lang' => 'en', 'id' => 1]);
        $this->routerModel->insertSlug($presenter, 'news/detail-1');

        // rozlozeni adresy
        Assert::equal(
            new Nette\Application\Request('News', 'GET', ['action' => 'detail', 'lang' => 'cs', 'id' => 1], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb.cz/novinky/detail-1')))
        );

        Assert::null($router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb.com/novinky/detail-1'))));
        Assert::null($router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb.com'))));

        Assert::equal(
            new Nette\Application\Request('News', 'GET', ['action' => 'detail', 'lang' => 'en', 'id' => 1], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb.com/news/detail-1')))
        );

        Assert::null($router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb.cz/news/detail-1'))));
        Assert::null($router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb.cz'))));
    }


    /**
     * @dataProvider config/databases.ini mysql
     */
    public function testLanguageDomainSwitch2()
    {
        $this->context->parameters['router']['languageDomainSwitch'] = true;
        $router = new \DatabaseRouter($this->context);

        // slozeni adresy
        Assert::equal(
            'http://NetteWeb.cz/novinky/detail-1',
            $router->constructUrl(new Nette\Application\Request('News', 'GET', ['lang' => 'cs', 'action' => 'detail', 'id' => 1], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb.cz'))
        );

        Assert::equal(
            'http://NetteWeb.com/news/detail-1',
            $router->constructUrl(new Nette\Application\Request('News', 'GET', ['lang' => 'en', 'action' => 'detail', 'id' => 1], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb.com'))
        );


        Assert::equal(
            'http://NetteWeb.cz/home/parametr2',
            $router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb.cz'))
        );

        Assert::equal(
            'http://NetteWeb.com/',
            $router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'en', 'action' => 'default'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb.com'))
        );
    }
}


/**
 * Class MiniPresenter
 * specialni trida simulujici presenter
 *
 * @author  geniv
 * @package Test
 */
class MiniPresenter extends \Nette\Application\UI\Presenter
{
    private $name;
    public $parameters = [];


    /**
     * defaultni konstruktor
     * @param $name
     * @param null $action
     * @param array $parameters
     */
    public function __construct($name, $action = NULL, $parameters = [])
    {
        $this->name = $name;
        $this->parameters = $parameters;
        if ($action) {
            $this->parameters += array('action' => $action);
        }
    }


    /**
     * nacitani jmena
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * nacitani akce
     * @param bool $fullyQualified
     * @return null
     */
    public function getAction($fullyQualified = false)
    {
        return (isset($this->parameters['action']) ? $this->parameters['action'] : NULL);
    }
}


//Spusteni testovacich metod
$testCase = new DatabaseRouterTest($container);
$testCase->run();
