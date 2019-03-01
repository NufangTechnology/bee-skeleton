<?php
require dirname(__DIR__) . '/vendor/autoload.php';

$stream = new \Bee\Logger\Adapter\Stream(__DIR__);

$stream->debug('我是一个debug', ['stet' => '我是一个bug']);