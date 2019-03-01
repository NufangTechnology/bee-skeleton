<?php
namespace Star\Util;

use Bee\Di\Container as Di;
use Bee\Http\Server;
use Bee\Router\Router;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server as SwooleHttpServer;
use Swoole\Server\Task as SwooleTask;

/**
 * HttpServer
 *
 * @package Eye\Util
 */
class HttpServer extends Server
{
    /**
     * Server启动在主进程的主线程回调此方法
     *
     * @param \Swoole\Http\Server $server
     */
    public function onStart(SwooleHttpServer $server)
    {
        swoole_set_process_name($this->name . ':reactor');
    }

    /**
     * Worker进程/Task进程启动时回调此方法
     *
     * @param \Swoole\Http\Server $server
     * @param integer $workerId
     */
    public function onWorkerStart(SwooleHttpServer $server, $workerId)
    {
        if ($server->taskworker) {
            swoole_set_process_name($this->name . ':task');
        } else {
            swoole_set_process_name($this->name . ':worker');
        }

        // 注册错误处理方法
        register_shutdown_function(function () {
//            PR('register_shutdown_function');
        });

        $di     = Di::getDefault();

        // 错误处理方法
        set_error_handler(function ($code, $message, $file, $line, $callStack) use ($di) {
//            PR('set_error_handler');
            PR(func_get_args());
//            PR(debug_backtrace());

            $notice = new Notice();

            $di->getShared('service.logger')->error($message);

        }, E_ALL);

        $router = new Router();
        // 挂载路由
        $router->map(require(CONFIG_PATH . '/routes.php'));

        $di->setShared('router', $router);
        $di->setShared('server', $server);
    }

    /**
     * worker进程终止时回调此方法
     *  - 在此函数中回收worker进程申请的各类资源
     *
     * @param \Swoole\Http\Server $server
     * @param integer $workerId
     */
    public function onWorkerStop(SwooleHttpServer $server, $workerId)
    {
//        PR('onWorkerStop');
//        PR($server);
    }

    /**
     * Http请求进来时回调此方法
     *
     * @param Request $request
     * @param Response $response
     */
    public function onRequest(Request $request, Response $response)
    {
        try {
            $di = Di::getDefault();

            (new Application($request, $response))
                ->map($di->getShared('config.middleware'))
                ->handle()
            ;
        } catch (\Throwable $e) {
            $response->end($e->getMessage());
        }
    }

    /**
     * 异步任务
     *
     * @param SwooleHttpServer $server
     * @param \Swoole\Server\Task $task
     */
    public function onTask(SwooleHttpServer $server, SwooleTask $task)
    {
        //任务的数据
        $params = $task->data;
        // 获取参数
        $class  = $params['class'];
        $method = $params['method'];
        $data   = $params['data'];

        // 调起应任务
        (new $class)->{$method}($data);
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
    public function onWorkerError(SwooleHttpServer $server, $workerId, $workerPid, $exitCode, $signal)
    {
//        PR('onWorkerError');
//        PR(func_get_args());
    }

    /**
     * 管理进程启动时
     *  - 本函数中可以修改管理进程的名称。
     *  - 注意，manager进程中不能添加定时器，不能使用task、async、coroutine等功能
     *  - onManagerStart回调时，Task和Worker进程已创建
     *
     * @param SwooleHttpServer $server
     * @return mixed
     */
    public function onManagerStart(SwooleHttpServer $server)
    {
        // TODO: Implement onManagerStart() method.
    }

    /**
     * 管理进程结束时回调该方法
     *  - onManagerStop触发时，说明Task和Worker进程已结束运行，已被Manager进程回收。
     *
     * @param SwooleHttpServer $server
     * @return mixed
     */
    public function onManagerStop(SwooleHttpServer $server)
    {
        // TODO: Implement onManagerStop() method.
    }
}