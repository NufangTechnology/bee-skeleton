<?php

require __DIR__ . '/../vendor/autoload.php';

$writer = new \Ahc\Cli\Output\Writer();

//$writer->write($writer->colorizer()->error('你他妈出错了你知道吗？'));

//$writer->write($writer->colorizer()->info('hah'));

$writer->table([
    ['a' => 'apple', 'b-c' => 'ball', 'c_d' => 'cat'],
    ['a' => 'applet', 'b-c' => 'bee', 'c_d' => 'cute'],
]);