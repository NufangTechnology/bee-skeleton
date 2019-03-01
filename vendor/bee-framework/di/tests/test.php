<?php
require dirname(__DIR__) . '/vendor/autoload.php';

class Test_1
{
    public $a = false;
}

class Test_2
{}

$di = new \Bee\Di\Container();
$di->set('test1', Test_1::class);
$di->set('test2', Test_2::class);

$test1 = new Test_1;

$d = \Bee\Di\Container::getDefault();

$a = new \Bee\Di\Container();
$s = $a->getServices();
$e = \Bee\Di\Container::getDefault();

$di->setShared('test1', $test1);

$test1->a = 567;

