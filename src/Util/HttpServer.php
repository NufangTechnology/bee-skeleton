<?php
namespace Star\Util;

use Bee\Http\Server;
use Phalcon\Di;
use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * HttpServer
 *
 * @package Eye\Util
 */
class HttpServer extends Server
{
    /**
     * @var Micro
     */
    private $micro;

    /**
     * @var Di
     */
    private $di;

    /**
     * Server启动在主进程的主线程回调此方法
     *
     * @param \Swoole\Http\Server $server
     */
    public function onStart(\Swoole\Http\Server $server)
    {
        swoole_set_process_name($this->name . ':reactor');
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

        // 创建容器
        $di    = Di::getDefault();
        // 实例化应用
        $micro = new Micro($di);

        // 注入http server
        $di->setShared('server', $server);
        // 注入request于response组件
        $di->setShared('request', \Bee\Http\Request::class);
        $di->setShared('response', \Bee\Http\Response::class);
        // 注册系统默认组件
        $di->setShared('filter',\Phalcon\Filter::class);
        $di->setShared('security',\Phalcon\Security::class);
        $di->setShared('router', \Phalcon\Mvc\Router::class);

        // 注册错误处理方法
        register_shutdown_function(function () use ($micro) {
            $micro->eventsManager->fire("log:handleShutdown", $micro);
            $micro->eventsManager->fire('micro:afterHandleShutdown', $micro);
        });
        // 错误处理方法
        set_error_handler(function () use ($micro) {
            $micro->eventsManager->fire("log:handleError", $micro, func_get_args());
        }, E_ALL);

        // micro注入事件管理器
        $micro->setEventsManager($di->getShared('eventsManager'));

        // 加载路由
        $routes = require(CONFIG_PATH . '/routes.php');
        foreach ($routes as $route) {
            $micro->mount($route);
        }

        $this->micro = $micro;
        $this->di    = $di;
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
        // 断开数据库连接
        $this->micro->db->close();
    }

    /**
     * Http请求进来时回调此方法
     *
     * @param Request $request
     * @param Response $response
     */
    public function onRequest(Request $request, Response $response)
    {
        $this->di->getShared('request')->withSource($request);
        $this->di->getShared('response')->withSource($response);

        // 开始处理业务
        $this->micro->handle();
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
    }
}