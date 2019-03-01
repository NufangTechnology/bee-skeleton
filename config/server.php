<?php
return [
    // http服务配置
    'http' => [
        'name'   => 'app-manage-http',
        'host'   => '0.0.0.0',
        'port'   => 8002,
        'option' => [
            'pid_file'              => RUNTIME_PATH . '/http.pid',
            'log_file'              => RUNTIME_PATH . '/http_server.log',
            'worker_num'            => 4,
            'task_worker_num'       => 4,
            'daemonize'             => true,
            'open_cpu_affinity'     => true,
            'max_request'           => 5000, // 单个worker处理请求数达到5000，自动退出
            'max_coroutine'         => 9000,
            'http_compression'      => true,
            'task_enable_coroutine' => true,
        ]
    ],

    // 定时器配置
    'worker' => [
        'name'     => 'app-manage-worker',
        'pidFile'  => RUNTIME_PATH . '/worker.pid',
        'daemon'   => true,
        'redirect' => false
    ],

    // 日志服务配置
    'logger' => [
        'base_dir'    => RUNTIME_PATH,
    ],
];
