<?php
require dirname(__DIR__) . '/vendor/autoload.php';


$configs_1 = [
    'master' => [
        [
            'host'        => '192.168.0.254',   //数据库ip
            'port'        => 3306,          //数据库端口
            'user'        => 'root',        //数据库用户名
            'password'    => '123456', //数据库密码
            'database'    => 'node_1',   //默认数据库名
            'timeout'     => 0.5,       //数据库连接超时时间
            'charset'     => 'utf8mb4', //默认字符集
            'strict_type' => true,  //ture，会自动表数字转为int类型
            'pool_size'   => 5,
        ],
    ],
    'salve' => [
        [
            'host'        => '192.168.0.254',   //数据库ip
            'port'        => 3307,          //数据库端口
            'user'        => 'root',        //数据库用户名
            'password'    => '123456', //数据库密码
            'database'    => 'node_1',   //默认数据库名
            'timeout'     => 0.5,       //数据库连接超时时间
            'charset'     => 'utf8mb4', //默认字符集
            'strict_type' => true,  //ture，会自动表数字转为int类型
            'pool_size'   => 5,
        ],
        [
            'host'        => '192.168.0.254',   //数据库ip
            'port'        => 3308,          //数据库端口
            'user'        => 'root',        //数据库用户名
            'password'    => '123456', //数据库密码
            'database'    => 'node_1',   //默认数据库名
            'timeout'     => 0.5,       //数据库连接超时时间
            'charset'     => 'utf8mb4', //默认字符集
            'strict_type' => true,  //ture，会自动表数字转为int类型
            'pool_size'   => 5,
        ],
        [
            'host'        => '192.168.0.254',   //数据库ip
            'port'        => 3309,          //数据库端口
            'user'        => 'root',        //数据库用户名
            'password'    => '123456', //数据库密码
            'database'    => 'node_1',   //默认数据库名
            'timeout'     => 0.5,       //数据库连接超时时间
            'charset'     => 'utf8mb4', //默认字符集
            'strict_type' => true,  //ture，会自动表数字转为int类型
            'pool_size'   => 5,
        ],
    ]
];

$configs_2 = [
    'master' => [
        'host'        => '127.0.0.1',   //数据库ip
        'port'        => 3306,          //数据库端口
        'user'        => 'root',        //数据库用户名
        'password'    => '123456', //数据库密码
        'database'    => 'test',   //默认数据库名
        'timeout'     => 0.5,       //数据库连接超时时间
        'charset'     => 'utf8mb4', //默认字符集
        'strict_type' => true,  //ture，会自动表数字转为int类型
    ],
    'salve' => [
        'host'        => '127.0.0.1',   //数据库ip
        'port'        => 3306,          //数据库端口
        'user'        => 'root',        //数据库用户名
        'password'    => '123456', //数据库密码
        'database'    => 'test',   //默认数据库名
        'timeout'     => 0.5,       //数据库连接超时时间
        'charset'     => 'utf8mb4', //默认字符集
        'strict_type' => true,  //ture，会自动表数字转为int类型
    ]
];

$start = microtime(true);

go(function () {

    $configs_3 = [
        'host'        => '192.168.0.254',   //数据库ip
        'port'        => 3306,          //数据库端口
        'user'        => 'root',        //数据库用户名
        'password'    => '123456', //数据库密码
        'database'    => 'node_1',   //默认数据库名
        'timeout'     => 0.5,       //数据库连接超时时间
        'charset'     => 'utf8mb4', //默认字符集
        'strict_type' => true,  //ture，会自动表数字转为int类型
        'pool_size'   => 5,
    ];

    $mysql = null;

    $mysql = new \Bee\Db\MySQL($configs_3);

    $list = [];

    $chan = new \Swoole\Coroutine\Channel(20);

    for ($i = 0; $i < 2; $i++) {
        go(function () use ($mysql, $chan) {
            $mysql->insert("insert into table_1(name, age) value ('hello', 28)");
            $data = $mysql->master('show databases');

            $chan->push($data);
        });
    }

    for ($i = 0; $i < 2; $i++) {
//        print_r($chan->pop());
        $list[] = $chan->pop();
    }

    print_r($list);

});

echo microtime(true) - $start, "\n";