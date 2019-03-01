<?php
require dirname(__DIR__) . '/vendor/autoload.php';

go(function () {

    $config = [
        'host' => '192.168.0.254',
        'port' => 6379,
        'options' => [
            'connect_timeout' => 1,
            'timeout'         => 1,
            'reconnect'       => 3,
//            'password'        => ''
        ],
        'pool_size' => 1,
    ];

    $conn = new \Bee\Db\Redis($config);
    $redis = $conn->getMasterConnect();
    $redis->set('12345678', 'word');

    $conn->putMasterConnect($redis);

    $conn->master(function (\Swoole\Coroutine\Redis $redis) {
        $redis->set('abcdefg', 'china, china, china');
    });
});