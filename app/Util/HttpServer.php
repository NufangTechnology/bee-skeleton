<?php
namespace Star\Util;

use Bee\Di\Container;
use Bee\Error\Notice;
use Bee\Http\Application;
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
     * @var Container
     */
    private $container;

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

        // 错误处理方法
        set_error_handler(function ($code, $message, $file, $line, $callStack) {
            ThrowExceptionHandler::error(new Notice($message, $code, $line, $file));
        }, E_ALL);

        // 路由处理
        $router    = new Router();
        // 挂载路由
        $router->map(require(CONFIG_PATH . '/routes.php'));

        // 路由与 server 注入容器全局共享
        $container = Container::getDefault();
        $container->setShared('router', $router);
        $container->setShared('server', $server);

        $this->container = $container;
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
            // 创建请求实例对象
            $app = new Application($request, $response);

            try {
                $app->map($this->container->getShared('config.middleware'))->handle();
            } catch (\Bee\Exception $e) {
                $response->end(ThrowExceptionHandler::http($e, $app->getContext()));
            }

        } catch (\Throwable $e) {
            $response->end(ThrowExceptionHandler::uncaught($e));
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
     */
    public function onWorkerError(SwooleHttpServer $server, $workerId, $workerPid, $exitCode, $signal)
    {
    }

    /**
     * 管理进程启动时
     *  - 本函数中可以修改管理进程的名称。
     *  - 注意，manager进程中不能添加定时器，不能使用task、async、coroutine等功能
     *  - onManagerStart回调时，Task和Worker进程已创建
     *
     * @param SwooleHttpServer $server
     */
    public function onManagerStart(SwooleHttpServer $server)
    {
    }

    /**
     * 管理进程结束时回调该方法
     *  - onManagerStop触发时，说明Task和Worker进程已结束运行，已被Manager进程回收。
     *
     * @param SwooleHttpServer $server
     */
    public function onManagerStop(SwooleHttpServer $server)
    {
    }
}