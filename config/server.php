<?php
return [
    // http服务配置
    'http' => [
        'name'   => 'bee-http',
        'host'   => '0.0.0.0',
        'port'   => 8000,
        'option' => [
            'pid_file'          => RUNTIME_PATH . '/http.pid',
            'log_file'          => RUNTIME_PATH . '/http_server.log',
            'worker_num'        => 8,
            'daemonize'         => true,
            'dispatch_mode'     => 3,
            'enable_coroutine'  => false,
            'open_cpu_affinity' => true,
            'max_request'       => 5000, // 单个worker处理请求数达到5000，自动退出
            'backlog'           => 1024,
        ]
    ],

    // MQ服务配置
    'mq' => [
        'name'     => 'group-mq',
        'pidFile'  => RUNTIME_PATH . '/mq.pid',
        'logFile'  => RUNTIME_PATH . '/mq.log',
        'daemon'   => true, // 以守护进程模式运行
        'redirect' => false, // 不启用标准输出
        'format'   => 'igbinary', // MQ数据格式
    ],

    // 部署服务配置
    'deploy' => [
        'host'  => 'http://host/', // 配置拉取接口地址
        'token' => '', // 身份令牌（来自部署用户组）
    ]
];