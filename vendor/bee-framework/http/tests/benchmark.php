<?php
namespace Bee\Tests;

use Bee\Http\Server;
use PHPUnit\Framework\TestCase;
use Swoole\Http\Request;
use Swoole\Http\Response;

class HttpServerTest extends Server
{
    /**
     * Server启动在主进程的主线程回调此方法
     *
     * @param \Swoole\Http\Server $server
     */
    public function onStart(\Swoole\Http\Server $server)
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
        $response->end('hello word');
    }

    /**
     * Worker进程/Task进程启动时回调此方法
     *
     * @param \Swoole\Http\Server $server
     * @param integer $workerId
     */
    public function onWorkerStart(\Swoole\Http\Server $server, $workerId)
    {
        // TODO: Implement onWorkerStart() method.
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
        // TODO: Implement onWorkerStop() method.
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
    }
}