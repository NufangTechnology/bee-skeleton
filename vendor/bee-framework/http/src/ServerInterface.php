<?php
namespace Bee\Http;

use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;
use Swoole\Server\Task;

/**
 * Interface ServerInterface
 *
 * @package Bee\Http
 */
interface ServerInterface
{
    /**
     * 管理进程启动时
     *  - 本函数中可以修改管理进程的名称。
     *  - 注意，manager进程中不能添加定时器，不能使用task、async、coroutine等功能
     *  - onManagerStart回调时，Task和Worker进程已创建
     *
     * @param Server $server
     * @return mixed
     */
    public function onManagerStart(Server $server);

    /**
     * 管理进程结束时回调该方法
     *  - onManagerStop触发时，说明Task和Worker进程已结束运行，已被Manager进程回收。
     *
     * @param Server $server
     * @return mixed
     */
    public function onManagerStop(Server $server);

    /**
     * Server启动在主进程的主线程回调此方法
     *
     * @param Server $server
     */
    public function onStart(Server $server);

    /**
     * Server正常结束时回调此方法
     *
     * @param Server $server
     */
    public function onShutdown(Server $server);

    /**
     * Worker进程/Task进程启动时回调此方法
     *
     * @param Server $server
     * @param integer $workerId
     */
    public function onWorkerStart(Server $server, $workerId);

    /**
     * worker进程终止时回调此方法
     *  - 在此函数中回收worker进程申请的各类资源
     *
     * @param Server $server
     * @param integer $workerId
     */
    public function onWorkerStop(Server $server, $workerId);

    /**
     * 异步重启特性
     *  - 旧的Worker进程在退出时，事件循环的每个周期结束时调用onWorkerExit通知Worker进程退出
     *  - 在onWorkerExit中尽可能地移除/关闭异步的Socket连接，
     *  - 最终底层检测到Reactor中事件监听的句柄数量为0时退出进程。
     *
     * @param Server $server
     * @param $workerId
     */
    public function onWorkerExit(Server $server, $workerId);

    /**
     * Http请求进来时回调此方法
     *
     * @param Request $request
     * @param Response $response
     */
    public function onRequest(Request $request, Response $response);

    /**
     * task异步回调处理任务时回调此方法
     *
     * @param Server $server
     * @param Task $task
     */
    public function onTask(Server $server, Task $task);

    /**
     * worker进程都低的任务完成后回调此方法
     *
     * @param Server $server
     * @param integer $taskId
     * @param mixed $data
     */
    public function onFinish(Server $server, $taskId, $data);

    /**
     * worker进程异常时回调此方法
     *
     * @param Server $server
     * @param integer $workerId
     * @param integer $workerPid
     * @param integer $exitCode
     * @param integer $signal
     *
     * @return mixed
     */
    public function onWorkerError(Server $server, $workerId, $workerPid, $exitCode, $signal);
}
