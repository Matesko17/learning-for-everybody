<?php

namespace Test;

use Tester\Assert;
use Tester;
use Nette;

$container = require __DIR__ . '/bootstrap.php';

/**
 * Class NetteRouterTest
 *
 * $ vendor/bin/tester tests/NetteRouterTest.phpt
 * $ vendor/bin/tester -c tests/config/php.ini tests
 *
 * @todo need to be fixed
 * @skip
 * 
 * @package Test
 */
class NetteRouterTest extends \Tester\TestCase
{
    private $router = NULL;

//'[<lang [a-z]{2}>/]<presenter>/<action>[/<id>]',
// // defaultni nette router
//         $router[] = new \Nette\Application\Routers\Route(
//             '[<lang [a-z]{2}>/]<presenter>/<action>[/<id>]',
//             array('presenter' => 'Homepage', 'action' => 'default', 'lang' => $this->lang)
//         // , Route::SECURED
//         );

    /**
     * defaultni konstruktor
     * @param Nette\DI\Container $container
     */
    public function __construct($container)
    {
        $this->router = new \Nette\Application\Routers\Route(
            '[<lang [a-z]{2}>/]<presenter>/<action>[/<id>][_<vp [0-9]+>]'
        // , array('presenter' => 'Homepage', 'action' => 'default', 'lang' => 'cs')
        // , Route::SECURED
        );    // vytvareni instance jako v RouterFactory
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
        $Parameters = ['action' => 'detail', 'lang' => null, 'id' => '123', 'vp' => null];
        $actual = $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/homepage/detail/123')));
        Assert::true($actual instanceof Nette\Application\Request);
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', $Parameters, [], [], ['secured' => false]),
            $actual
        );

        // Assert::equal(
        //     new Nette\Application\Request('Homepage', 'GET', $Parameters+['lang'=>'cs'], [], [], ['secured' => false]),
        //     $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/cs/homepage/detail/123')))
        // );
    }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testMatch2()
    // {
    //     Assert::equal(
    //         new Nette\Application\Request('Homepage', 'GET', ['action' => 'default', 'lang' => 'cs', 'vp' => '22'], [], [], ['secured' => false]),
    //         $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detailik/detail-abc_22')))
    //     );
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testMatch3()
    // {
    //     Assert::equal(
    //         new Nette\Application\Request('Homepage', 'GET', ['action' => 'default', 'lang' => 'cs', 'vp' => '9'], [], [], ['secured' => false]),
    //         $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detailik/detail-abc_9')))
    //     );
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testMatch4()
    // {
    //     Assert::equal(
    //         new Nette\Application\Request('Homepage', 'GET', ['action' => 'default', 'lang' => 'cs', 'vp' => '0'], [], [], ['secured' => false]),
    //         $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detailik/detail-abc_0')))
    //     );
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testMatch5()
    // {
    //     Assert::null($this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detailik/detail-abc_'))));
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testMatch6()
    // {
    //     Assert::null($this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/neco-jineho'))));
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testMatch7()
    // {
    //     Assert::null($this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/'))));
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testMatch8()
    // {
    //     Assert::null($this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/ahoj_1'))));
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testMatch9()
    // {
    //     // vyhodnoti anglictinu a prepne jazykvou sluzbu na anglictinu
    //     Assert::null($this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/en/home/detailik/detail-abc_22'))));

    //     $this->routerModel->getLanguageService()->setLang('cs');    // vraceni jazyka na CS
    // }


    /**
     * test na ajax
     */
    public function testMatch10()
    {
        $router = new \Nette\Application\Routers\Route('[<lang [a-z]{2}>/]<presenter>/<action>[/<id>][_<vp [0-9]+>]', ['presenter' => 'Homepage', 'action' => 'default', 'lang' => 'cs']);    // vytvareni instance jako v RouterFactory
        Assert::equal(
            new Nette\Application\Request('Homepage', 'GET', ['action' => 'default', 'lang' => 'cs', Nette\Application\UI\Presenter::SIGNAL_KEY => 'LiveSearch', 'q' => 'alb', 'vp' => null, 'id' => null], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/?do=LiveSearch&q=alb')))
        );
    }


    // /**
    //  * test na nadbytecne parametry 1
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testMatch11()
    // {
    //     Assert::equal(
    //         new Nette\Application\Request('Homepage', 'GET', ['action' => 'default', 'lang' => 'cs', 'huh' => 'ccc'], [], [], ['secured' => false]),
    //         $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detailik/detail-abc?huh=ccc')))
    //     );
    // }


    // /**
    //  * test na nadbytecne parametry 2
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testMatch12()
    // {
    //     Assert::equal(
    //         new Nette\Application\Request('Homepage', 'GET', ['action' => 'default', 'lang' => 'cs', 'vp' => '46', 'huha' => 'ddd'], [], [], ['secured' => false]),
    //         $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detailik/detail-abc_46?huha=ddd')))
    //     );
    // }


    /**
     * test strankovani na HP
     */
    public function testMatch13()
    {
        Assert::null($this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/_223'))));
        Assert::null($this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/?vp=223'))));

        $router = new \Nette\Application\Routers\Route('[<lang [a-z]{2}>/]<presenter>/<action>[/<id>][_<vp [0-9]+>]', ['presenter' => 'Homepage', 'action' => 'default', 'lang' => 'cs']);    // vytvareni instance jako v RouterFactory
        Assert::equal(
            new Nette\Application\Request('A', 'GET', ['action' => 'b', 'lang' => 'cs', 'vp' => '224', 'id' => null], [], [], ['secured' => false]),
            $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/a/b_224')))
        );
    }


    // /**
    //  * test konstrukce adresy
    //  */


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testConstructUrl1()
    // {
    //     Assert::equal(
    //         'http://NetteWeb/home/detailik/detail-abc',
    //         $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
    //     );
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testConstructUrl2()
    // {
    //     Assert::equal(
    //         'http://NetteWeb/home/detailik/detail-abc_0',
    //         $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'vp' => '0'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
    //     );
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testConstructUrl3()
    // {
    //     Assert::equal(
    //         'http://NetteWeb/home/detailik/detail-abc_22',
    //         $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'vp' => '22'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
    //     );
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testConstructUrl4()
    // {
    //     Assert::equal(
    //         'http://NetteWeb/home/detailik/detail-abc',
    //         $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'vp' => NULL], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
    //     );
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testConstructUrl5()
    // {
    //     Assert::equal(
    //         'http://NetteWeb/home/detailik/detail-abc?huhu=bbbb',
    //         $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'vp' => NULL, 'huhu' => 'bbbb'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
    //     );
    // }


    // /**
    //  * ostatni testy
    //  */


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testMetadata1()
    // {
    //     // spravne smerovani pri prazdne adrese
    //     $router = new \DatabaseRouter($this->context, NULL);

    //     Assert::null($router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb'))));
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testMetadata2()
    // {
    //     // spravne smerovani pri prazdne adrese
    //     $router = new \DatabaseRouter($this->context, NULL, ['presenter' => 'Homepage']);

    //     Assert::equal(
    //         new Nette\Application\Request('Homepage', 'GET', [], [], [], ['secured' => false]),
    //         $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb')))
    //     );
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testMetadata3()
    // {
    //     // spravne smerovani pri prazdne adrese
    //     $router = new \DatabaseRouter($this->context, NULL, ['presenter' => 'NewsPage']);

    //     Assert::equal(
    //         new Nette\Application\Request('NewsPage', 'GET', [], [], [], ['secured' => false]),
    //         $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb')))
    //     );
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testMetadata4()
    // {
    //     // spravne smerovani pri prazdne adrese
    //     $router = new \DatabaseRouter($this->context, NULL, ['presenter' => 'NewsPage', 'lang' => 'cs']);

    //     Assert::equal(
    //         new Nette\Application\Request('NewsPage', 'GET', ['lang' => 'cs'], [], [], ['secured' => false]),
    //         $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb')))
    //     );
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testMetadata5()
    // {
    //     // spravne smerovani pri prazdne adrese
    //     $router = new \DatabaseRouter($this->context, NULL, ['presenter' => 'NewsPage', 'lang' => 'es']);

    //     Assert::equal(
    //         new Nette\Application\Request('NewsPage', 'GET', ['lang' => 'es'], [], [], ['secured' => false]),
    //         $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb')))
    //     );
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testMetadata6()
    // {
    //     // spravne smerovani pri prazdne adrese
    //     $router = new \DatabaseRouter($this->context, NULL, ['presenter' => 'NewsPage', 'lang' => 'es', 'action' => 'hokus']);

    //     Assert::equal(
    //         new Nette\Application\Request('NewsPage', 'GET', ['lang' => 'es', 'action' => 'hokus'], [], [], ['secured' => false]),
    //         $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb')))
    //     );
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testOneWay()
    // {
    //     $router = new \DatabaseRouter($this->context, NULL, [], \DatabaseRouter::ONE_WAY);
    //     Assert::null(
    //         $router->constructUrl(
    //             new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default'], [], [], ['secured' => false]),
    //             new Nette\Http\UrlScript('http://NetteWeb/home/detailik/detail-abc'))
    //     );//                dump($c, $parameters);
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testHttps()
    // {
    //     $router = new \DatabaseRouter($this->context, NULL, [], \DatabaseRouter::SECURED);
    //     Assert::equal(
    //         'https://NetteWeb/home/detailik/detail-abc',
    //         $router->constructUrl(
    //             new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default'], [], [], ['secured' => TRUE]),
    //             new Nette\Http\UrlScript('http://NetteWeb'))
    //     );
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testMatchAlias()
    // {
    //     $presenter = new MiniPresenter('Homepage', 'default', ['lang' => 'cs',]);

    //     // test vlozeni slugu
    //     sleep(1);   // prodleva kvuli casovemu razitku
    //     $this->routerModel->insertSlug($presenter, 'home/detail/detail');
    //     sleep(1);   // prodleva kvuli casovemu razitku
    //     $this->routerModel->insertSlug($presenter, 'home/detailovy/parametr');

    //     // puvodni - prvni
    //     Assert::equal(
    //         new Nette\Application\Request('Homepage', 'GET', ['action' => 'default', 'lang' => 'cs'], [], [], ['secured' => false]),
    //         $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detailik/detail-abc')))
    //     );

    //     // varianta 1
    //     Assert::equal(
    //         new Nette\Application\Request('Homepage', 'GET', ['action' => 'default', 'lang' => 'cs'], [], [], ['secured' => false]),
    //         $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detail/detail')))
    //     );

    //     // varianta 2 - posledni
    //     Assert::equal(
    //         new Nette\Application\Request('Homepage', 'GET', ['action' => 'default', 'lang' => 'cs'], [], [], ['secured' => false]),
    //         $this->router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/detailovy/parametr')))
    //     );

    //     // slozena adresa obsahu posledni adresu
    //     Assert::equal(
    //         'http://NetteWeb/home/detailovy/parametr',
    //         $this->router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
    //     );
    // }


    // /**
    //  * @dataProvider config/databases.ini sqlite3
    //  */
    // public function testMetadataParameters()
    // {
    //     // spravne smerovani pri prazdne adrese
    //     $router = new \DatabaseRouter($this->context, ['gg', 'bb']);

    //     // puvodni
    //     $presenter = new MiniPresenter('Homepage', 'default', ['lang' => 'cs', 'gg' => '123']);
    //     $this->routerModel->insertSlug($presenter, 'home/parametr');

    //     // zmena 1
    //     sleep(1);   // prodleva kvuli casovemu razitku
    //     $presenter = new MiniPresenter('Homepage', 'default', ['lang' => 'cs', 'bb' => '321']);
    //     $this->routerModel->insertSlug($presenter, 'home/parametr1');

    //     // zmena 2
    //     sleep(1);   // prodleva kvuli casovemu razitku
    //     $presenter = new MiniPresenter('Homepage', 'default', ['lang' => 'cs', 'gg' => '456', 'bb' => '789']);
    //     $this->routerModel->insertSlug($presenter, 'home/parametr2');

    //     Assert::equal(
    //         new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'gg' => '123'], [], [], ['secured' => false]),
    //         $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/parametr')))
    //     );

    //     Assert::equal(
    //         new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'bb' => '321'], [], [], ['secured' => false]),
    //         $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/parametr1')))
    //     );

    //     Assert::equal(
    //         new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'gg' => '456', 'bb' => '789'], [], [], ['secured' => false]),
    //         $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/parametr2')))
    //     );

    //     // vice parametru navic
    //     Assert::equal(
    //         new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'gg' => '456', 'bb' => '789', 'hu' => 'aaa'], [], [], ['secured' => false]),
    //         $router->match(new Nette\Http\Request(new Nette\Http\UrlScript('http://NetteWeb/home/parametr2?hu=aaa')))
    //     );

    //     ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //     // zpetna kontrola na slozeni (puvodni)
    //     Assert::equal(
    //         'http://NetteWeb/home/parametr2',
    //         $router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'gg' => '123'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
    //     );

    //     // zpetna kontrola na slozeni (zmena 1)
    //     Assert::equal(
    //         'http://NetteWeb/home/parametr2',
    //         $router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'bb' => '321'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
    //     );

    //     // zpetna kontrola na slozeni (zmena 2)
    //     Assert::equal(
    //         'http://NetteWeb/home/parametr2',
    //         $router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'gg' => '456', 'bb' => '789'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
    //     );

    //     // zpetna kontrola na slozeni (zmena 2)
    //     Assert::equal(
    //         'http://NetteWeb/home/parametr2?hu=aaa',
    //         $router->constructUrl(new Nette\Application\Request('Homepage', 'GET', ['lang' => 'cs', 'action' => 'default', 'gg' => '456', 'bb' => '789', 'hu' => 'aaa'], [], [], ['secured' => false]), new Nette\Http\UrlScript('http://NetteWeb'))
    //     );
    // }
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
$testCase = new NetteRouterTest($container);
$testCase->run();
