<?php
namespace Star\Util;

use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;
use Star\Util\Throwable\AbstractRuntimeError;
use Star\Util\Throwable\AbstractRuntimeException;

/**
 * 日志
 *
 * @package Star\Util
 */
abstract class LogCollector
{
    /**
     * 应用日志名称
     *
     * @var string
     */
    protected $name = 'HTTP';

    /**
     * 记录 HTTP 请求前置日志
     *
     * @param Event $event
     * @param Micro $micro
     */
    public function beforeHandle(Event $event, Micro $micro)
    {
        $logger = $micro->getSharedService('service.logger.http');
        $global = $micro->getSharedService('global');

        $record = [
            'unique_id'  => $global['uniqueId'],
            'auth_token' => $global['authToken'],
            'label'      => "{$this->name}:before",
            'trace'      => [
                'url'         => $micro->request->getURI(),
                'ip'          => $micro->request->getClientAddress(),
                'http_method' => $micro->request->getMethod(),
                'referrer'    => $micro->request->getHTTPReferer(),
                'get'         => $micro->request->getQuery(),
                'body'        => $micro->request->getRawBody(),
                'server'      => $_SERVER,
            ]
        ];

        $logger->info('HTTP 请求前置日志', $record);
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
        $logger = $micro->getSharedService('service.logger.http');
        $global = $micro->getSharedService('global');

        $record = [
            'unique_id'  => $global['uniqueId'],
            'auth_token' => $global['authToken'],
            'label'      => "{$this->name}:running",
            'trace'     => $params
        ];

        $logger->info('HTTP 运行时日志', $record);
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
        $logger = $micro->getSharedService('service.logger.http');
        $global = $micro->getSharedService('global');

        $record = [
            'unique_id'  => $global['uniqueId'],
            'auth_token' => $global['authToken'],
            'label'      => "{$this->name}:after",
            'trace'     => $params
        ];

        $logger->info('HTTP 运行时日志', $record);
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
        $logger = $micro->getSharedService('service.logger.http');
        $global = $micro->getSharedService('global');

        $record = [
            'unique_id'  => $global['uniqueId'],
            'auth_token' => $global['authToken'],
            'label'      => "{$this->name}:exception",
            'trace'     => []
        ];

        // 获取手动抛出异常信息
        if ($exception instanceof AbstractRuntimeException) {
            $record['trace']['data'] = $exception->getData();
            $record['trace']['args'] = $exception->getArgs();
        }
        // 错误 trace 栈
        $record['trace']['exception'] = $exception->getTrace();

        $logger->error('HTTP Exception', $record);
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
        $logger = $micro->getSharedService('service.logger.http');
        $global = $micro->getSharedService('global');

        $record = [
            'unique_id'  => $global['uniqueId'],
            'auth_token' => $global['authToken'],
            'label'      => "{$this->name}:error",
            'trace'     => []
        ];

        // 获取手动抛出异常信息
        if ($exception instanceof AbstractRuntimeError) {
            $record['trace']['data'] = $exception->getData();
            $record['trace']['args'] = $exception->getArgs();
        }
        // 异常 trace
        $record['trace']['error'] = $exception->getTrace();

        $logger->critical('HTTP Error', $record);
    }

    /**
     * 记录 HTTP 请求导致进程异常日志
     *
     * @param Event $event
     * @param Micro $micro
     */
    public function shutdown(Event $event, Micro $micro)
    {
        $logger = $micro->getSharedService('service.logger.http');
        $global = $micro->getSharedService('global');

        $record = [
            'unique_id'  => $global['uniqueId'],
            'auth_token' => $global['authToken'],
            'label'      => "{$this->name}:shutdown",
            'trace'     => error_get_last()
        ];

        $logger->alert('HTTP Shutdown', $record);
    }
}
