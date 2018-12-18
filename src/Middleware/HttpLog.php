<?php
namespace Star\Middleware;

use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;
use Star\Util\LogLabel;
use Star\Util\Throwable\AbstractError;
use Star\Util\Throwable\AbstractException;

class HttpLog
{
    /**
     * 记录 HTTP 请求前置日志
     *
     * @param Event $event
     * @param Micro $micro
     */
    public function beforeHandle(Event $event, Micro $micro)
    {
        $logger = $micro->getSharedService('logger');
        $global = $micro->getSharedService('global');

        $extra = [
            'url'         => $micro->request->getURI(),
            'ip'          => $micro->request->getClientAddress(),
            'http_method' => $micro->request->getMethod(),
            'referrer'    => $micro->request->getHTTPReferer(),
            'get'         => $micro->request->getQuery(),
            'body'        => $micro->request->getRawBody(),
            'server'      => $_SERVER,
        ];

        $logger->info(LogLabel::HTTP_BEFORE, $global['authToken'], $global['uniqueId'], $extra);
    }

    /**
     * 记录 HTTP 运行时日志
     *
     * @param Event $event
     * @param Micro $micro
     * @param array $params
     */
    public function handle(Event $event, Micro $micro, array $params = [])
    {
        $logger = $micro->getSharedService('logger');
        $global = $micro->getSharedService('global');

        $logger->info(LogLabel::HTTP_RUNNING, $global['authToken'], $global['uniqueId'], $params);
    }

    /**
     * 记录 HTTP 结束日志
     *  - 给客户端响应的数据
     *
     * @param Event $event
     * @param Micro $micro
     * @param array $params
     */
    public function afterHandle(Event $event, Micro $micro, array $params = [])
    {
        $logger = $micro->getSharedService('logger');
        $global = $micro->getSharedService('global');

        // 错误 trace 栈
        $logger->info(LogLabel::HTTP_AFTER, $global['authToken'], $global['uniqueId'], $params);
    }

    /**
     * 记录 HTTP 异常日志
     *
     * @param Event $event
     * @param Micro $micro
     * @param \Exception $exception
     */
    public function throwException(Event $event, Micro $micro, \Exception $exception)
    {
        $logger = $micro->getSharedService('logger');
        $global = $micro->getSharedService('global');

        // 获取手动抛出异常信息
        if ($exception instanceof AbstractException) {
            $extra['data'] = $exception->getData();
            $extra['args'] = $exception->getArgs();
        }
        // 错误 trace 栈
        $extra['trace'] = $exception->getTrace();

        $logger->error(LogLabel::HTTP_EXCEPTION, $global['authToken'], $global['uniqueId'], $extra);
    }

    /**
     * 记录 HTTP 错误日志
     *
     * @param Event $event
     * @param Micro $micro
     * @param \Error $exception
     */
    public function throwError(Event $event, Micro $micro, \Error $exception)
    {
        $logger = $micro->getSharedService('logger');
        $global = $micro->getSharedService('global');

        // 获取手动抛出异常信息
        if ($exception instanceof AbstractError) {
            $extra['data'] = $exception->getData();
            $extra['args'] = $exception->getArgs();
        }
        // 异常 trace
        $extra['trace'] = $exception->getTrace();

        $logger->critical(LogLabel::HTTP_ERROR, $global['authToken'], $global['uniqueId'], $extra);
    }

    /**
     * 记录 HTTP 请求导致进程异常日志
     *
     * @param Event $event
     * @param Micro $micro
     */
    public function shutdown(Event $event, Micro $micro)
    {
        $logger = $micro->getSharedService('logger');
        $global = $micro->getSharedService('global');

        $logger->alert(LogLabel::HTTP_SHUTDOWN, $global['authToken'], $global['uniqueId'], error_get_last());
    }
}
