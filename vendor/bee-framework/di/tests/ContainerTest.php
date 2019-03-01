<?php
namespace Bee\Di\Tests;

use Bee\Di\Container;
use Bee\Di\Exception;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    /**
     * @var Container
     */
    private $container;

    public function setUp()
    {
        $this->container = new Container;
    }

    /**
     * @throws \Bee\Di\Exception
     */
    public function testSet()
    {
        $this->container->set('test1', Test_1::class);
        $this->container->set('test2', Test_2::class);

        $this->assertInstanceOf(Test_1::class, $this->container->get('test1'));
        $this->assertInstanceOf(Test_2::class, $this->container->get('test2'));

        $this->assertEquals(2, count($this->container->getServices()));
    }

    public function testSetShared()
    {
        $test1 = new Test_1();

        $this->container->setShared('test1', $test1);

        $test1->a = 567;

        $this->assertEquals($test1, $this->container->getShared('test1'));
    }

    /**
     * @expectedException Exception
     */
    public function testNotExistService()
    {
        $this->container->get('hahaha');
    }
}

class Test_1
{
    public $a = false;
}

class Test_2
{}