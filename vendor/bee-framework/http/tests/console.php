<?php
require __DIR__ . '/../vendor/autoload.php';

register_shutdown_function(function () {
    file_put_contents(__DIR__ . '/shutdown.log', json_encode(func_get_args()) . PHP_EOL, 8);
});

set_error_handler(function () {
    file_put_contents(__DIR__ . '/error.log', json_encode(func_get_args()) . PHP_EOL, 8);
}, E_ALL);

set_exception_handler(function (\Throwable $e) {
    file_put_contents(__DIR__ . '/exception.log', get_class($e) . PHP_EOL, 8);
});

class HttpServer extends \Bee\Http\Server
{
    /**
     * Server启动在主进程的主线程回调此方法
     *
     * @param \Swoole\Http\Server $server
     */
    public function onStart(\Swoole\Http\Server $server)
    {
        swoole_set_process_name($this->name . ':master');
    }

    /**
     * Worker进程/Task进程启动时回调此方法
     *
     * @param \Swoole\Http\Server $server
     * @param integer $workerId
     */
    public function onWorkerStart(\Swoole\Http\Server $server, $workerId)
    {
        if ($server->taskworker) {
            swoole_set_process_name($this->name . ':task');
        } else {
            swoole_set_process_name($this->name . ':worker');
        }

//        register_shutdown_function(function () {
//            file_put_contents(__DIR__ . '/shutdown.log', json_encode(func_get_args()) . PHP_EOL, 8);
//        });
//
//        set_error_handler(function () {
//            file_put_contents(__DIR__ . '/error.log', json_encode(func_get_args()) . PHP_EOL, 8);
//        }, E_ALL);
//
//        set_exception_handler(function (\Throwable $e) {
//            file_put_contents(__DIR__ . '/exception.log', get_class($e) . PHP_EOL, 8);
//        });
    }

    /**
     * worker进程终止时回调此方法
     *  - 在此函数中回收worker进程申请的各类资源
     *
     * @param \Swoole\Http\Server $server
     * @param integer $workerId
     */
    public function onWorkerStop(\Swoole\Http\Server $server, $workerId)
    {
        file_put_contents(__DIR__ . '/onWorkerStop.log', $workerId . PHP_EOL, 8);
    }

    /**
     * Http请求进来时回调此方法
     *
     * @param \Swoole\Http\Request $request
     * @param \Swoole\Http\Response $response
     */
    public function onRequest(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
        try {
            include 't.php';
        } catch (\Throwable $e) {
            file_put_contents(__DIR__ . '/throwable.log', get_class($e) . PHP_EOL, 8);
            $response->end(get_class($e));
            return;
        }

        $response->end(json_encode($request->server));
    }

    /**
     * worker进程异常时回调此方法
     *
     * @param \Swoole\Http\Server $server
     * @param integer $workerId
     * @param integer $workerPid
     * @param integer $exitCode
     * @param integer $signal
     *
     * @return mixed
     */
    public function onWorkerError(\Swoole\Http\Server $server, $workerId, $workerPid, $exitCode, $signal)
    {
        // TODO: Implement onWorkerError() method.

        file_put_contents(__DIR__ . '/onWorkerError.log', "{$workerId} - $workerPid - $exitCode - $signal" . PHP_EOL, 8);
    }

    /**
     * 管理进程启动时
     *  - 本函数中可以修改管理进程的名称。
     *  - 注意，manager进程中不能添加定时器，不能使用task、async、coroutine等功能
     *  - onManagerStart回调时，Task和Worker进程已创建
     *
     * @param \Swoole\Http\Server $server
     * @return mixed
     */
    public function onManagerStart(\Swoole\Http\Server $server)
    {
        // TODO: Implement onManagerStart() method.

        swoole_set_process_name($this->name . ':manager');
    }

    /**
     * 管理进程结束时回调该方法
     *  - onManagerStop触发时，说明Task和Worker进程已结束运行，已被Manager进程回收。
     *
     * @param \Swoole\Http\Server $server
     * @return mixed
     */
    public function onManagerStop(\Swoole\Http\Server $server)
    {
        // TODO: Implement onManagerStop() method.

        file_put_contents(__DIR__ . '/onManagerStop.log', $server->worker_id . PHP_EOL, 8);
    }
}

$httpServer = new HttpServer(
    [
        'name'   => 'bee-http',
        'host'   => '0.0.0.0',
        'port'   => 8000,
        'option' => [
            'pid_file'         => __DIR__ . '/bee-http.pid',
            'log_file'         => __DIR__ . '/bee-http_server.log',
            'task_worker_num'  => 0,
        ]
    ]
);
//$httpServer->start();

switch ($argv[1]) {
    case 'start':
        $httpServer->start();
        break;

    case 'stop':
        $httpServer->stop();
        break;

    case 'restart':
        $httpServer->restart();
        break;

    case 'status':
        $httpServer->status();
        break;

    case 'shutdown':
        $httpServer->shutdown();
        break;
}
