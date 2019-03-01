<?php
return [
    'mysql' => [
        'master' => [
            'host'        => 'gz-cdb-4ponckvj.sql.tencentcdb.com',   //数据库ip
            'port'        => 61977,          //数据库端口
            'user'        => 'xhb',        //数据库用户名
            'password'    => "xiaoheiban123",
            'database'    => 'service_management',   //默认数据库名
            'timeout'     => 0.5,       //数据库连接超时时间
            'charset'     => 'utf8mb4', //默认字符集
            'pool_size'   => 16,  // 连接池连接数量
        ],
        'slave'  => [
            'host'        => 'gz-cdb-4ponckvj.sql.tencentcdb.com',   //数据库ip
            'port'        => 61977,          //数据库端口
            'user'        => 'xhb',        //数据库用户名
            'password'    => "xiaoheiban123",
            'database'    => 'service_management',   //默认数据库名
            'timeout'     => 0.5,       //数据库连接超时时间
            'charset'     => 'utf8mb4', //默认字符集
            'pool_size'   => 24, // 连接池连接数量
        ]
    ],

    'redis' => [
        'master' => [
            'host'      => '134.175.189.242',
            'port'      => 9052,
            'auth'      => 'xiaoheiban123!@#',
            'timeout'   => 1,
            'pool_size' => 16, // 连接池连接数量
        ],
        'slave'  => [
            'host'      => '134.175.189.242',
            'port'      => 9052,
            'auth'      => 'xiaoheiban123!@#',
            'timeout'   => 1,
            'pool_size' => 24, // 连接池连接数量
        ]
    ]
];
