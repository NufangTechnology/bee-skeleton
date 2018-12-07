<?php
return [
    // MySQL配置 - 开发环境生成模型
    'database' => [
        "host"     => 'host',
        'username' => "user",
        'password' => "pass",
        'dbname'   => "db",
        'port'     => 3306,
        'charset'  => 'utf8mb4'
    ],

    // 应用配置 - 开发环境使用
    'application' => [
        'modelsDir' => __DIR__ . '/src/Model/'
    ],
];