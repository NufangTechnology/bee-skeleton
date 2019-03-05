<?php
return [
    'mysql' => [
        'master' => [
            'host'        => 'master_node',   //数据库ip
            'port'        => 3306,          //数据库端口
            'user'        => 'xhb',        //数据库用户名
            'password'    => "",
            'database'    => '',   //默认数据库名
            'timeout'     => 0.5,       //数据库连接超时时间
            'charset'     => 'utf8mb4', //默认字符集
            'pool_size'   => 16,  // 连接池连接数量
        ],
        'slave'  => [
            'host'        => 'slave_node',   //数据库ip
            'port'        => 3306,          //数据库端口
            'user'        => 'xhb',        //数据库用户名
            'password'    => "",
            'database'    => '',   //默认数据库名
            'timeout'     => 0.5,       //数据库连接超时时间
            'charset'     => 'utf8mb4', //默认字符集
            'pool_size'   => 16,  // 连接池连接数量
        ]
    ],

    'redis' => [
        'master' => [
            'host'      => 'master_node',  // host
            'port'      => 6319,  // 端口号
            'auth'      => '', // 密码
            'timeout'   => 1,  // 连接超时时间
            'pool_size' => 16, // 连接池连接数量
        ],
        'slave'  => [
            'host'      => 'slave_node',
            'port'      => 6319,
            'auth'      => '',
            'timeout'   => 1,
            'pool_size' => 16,
        ]
    ]
];
