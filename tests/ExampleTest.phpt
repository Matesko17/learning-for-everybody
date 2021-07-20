<?php

namespace Tests;

use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

$container = require __DIR__."/bootstrap.php";

/**
 * Class ExampleTest
 *
 * @author Jan Hermann <jan.hermann@q2.cz>
 * @package Qetteweb
 */
class ExampleTest extends TestCase
{
    private $context = null;
    
    /**
     * defaultni konstruktor
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->context = $container;
    }
    
    /**
     * samotne testy
     */
    
    
    public function testExample()
    {
        Assert::true(true);
        Assert::false(false);
        
        Assert::equal(" ", " ");
        
        Assert::true($this->context instanceof Container);
    }
}

$testCase = new ExampleTest($container);
$testCase->run();
