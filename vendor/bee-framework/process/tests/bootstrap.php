<?php
require __DIR__ . '/../vendor/autoload.php';

$watcher = new \Bee\Process\Watcher();

$watcher->watch(
    [
        __DIR__ . '/../src'
    ],
    function () {
        echo time() . PHP_EOL;
    }
);
$watcher->run();

//class Mater extends \Ant\Process\Master
//{
//    public function handle()
//    {
//    }
//
//    public function error()
//    {
//    }
//
//    public function exception(\Ahc\Cli\Exception $e)
//    {
//    }
//
//    public function shutdown()
//    {
//    }
//}


