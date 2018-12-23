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
        $logger = $micro->getSharedService('service.logger');
        $global = $micro->getSharedService('global');

        $record = [
            'unique_id' => $global['uniqueId'],
            'user_id'   => $global['userId'],
            'label'     => "{$this->name}:before",
            'create_at' => date('Y-m-d H:i:s'),
            'extra'     => [
                'get'    => $micro->request->getQuery(),
                'body'   => $micro->request->getRawBody(),
                'server' => $_SERVER,
            ]
        ];

        $logger->info("{$this->name} 请求前置日志", $record);
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
        $logger = $micro->getSharedService('service.logger');
        $global = $micro->getSharedService('global');

        $record = [
            'unique_id' => $global['uniqueId'],
            'user_id'   => $global['userId'],
            'label'     => "{$this->name}:running",
            'extra'     => $params
        ];

        $logger->info("{$this->name} 运行时日志", $record);
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
        $logger = $micro->getSharedService('service.logger');
        $global = $micro->getSharedService('global');

        $record = [
            'unique_id' => $global['uniqueId'],
            'user_id'   => $global['userId'],
            'label'     => "{$this->name}:after",
            'extra'     => [
                // 记录内容使用情况
                'memory_peak_usage' => formatBytes(memory_get_peak_usage(true)),
                'memory_usage'      => formatBytes(memory_get_usage(true)),
            ]
        ];

        $logger->info("{$this->name} 运行时日志", $record);
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
        $logger = $micro->getSharedService('service.logger');
        $global = $micro->getSharedService('global');

        $record = [
            'unique_id'  => $global['uniqueId'],
            'user_id'   => $global['user_id'],
            'label'      => "{$this->name}:exception",
            'extra'     => []
        ];

        // 获取手动抛出异常信息
        if ($exception instanceof AbstractRuntimeException) {
            $record['extra']['data'] = $exception->getData();
            $record['extra']['args'] = $exception->getArgs();
        }
        // 错误 trace 栈
        $record['extra']['exception'] = $exception->getTrace();

        // 记录内容使用情况
        $record['extra']['memory_peak_usage'] = formatBytes(memory_get_peak_usage(true));
        $record['extra']['memory_usage'] = formatBytes(memory_get_usage(true));

        $logger->error("{$this->name} Exception", $record);
    }

    /**
     * 记录 HTTP 错误日志
     *
     * @param Event $event
     * @param Micro $micro
     * @param array $error
     */
    public function throwError(Event $event, Micro $micro, array $error = [])
    {
        $logger = $micro->getSharedService('service.logger');
        $global = $micro->getSharedService('global');

        $record = [
            'unique_id' => $global['uniqueId'],
            'user_id'   => $global['userId'],
            'label'     => "{$this->name}:error",
            'extra'     => [
                'error' => $error,
                // 记录内容使用情况
                'memory_peak_usage' => formatBytes(memory_get_peak_usage(true)),
                'memory_usage'      => formatBytes(memory_get_usage(true)),
            ]
        ];

        $logger->critical("{$this->name} Error", $record);
    }

    /**
     * 记录 HTTP 请求导致进程异常日志
     *
     * @param Event $event
     * @param Micro $micro
     */
    public function shutdown(Event $event, Micro $micro)
    {
        $logger = $micro->getSharedService('service.logger');
        $global = $micro->getSharedService('global');

        $record = [
            'unique_id'  => $global['uniqueId'],
            'user_id'   => $global['userId'],
            'label'      => "{$this->name}:shutdown",
            'extra'     => [
                'shutdown' => error_get_last(),
                // 记录内容使用情况
                'memory_peak_usage' => formatBytes(memory_get_peak_usage(true)),
                'memory_usage'      => formatBytes(memory_get_usage(true)),
            ]
        ];

        $logger->alert("{$this->name} Shutdown", $record);
    }
}
