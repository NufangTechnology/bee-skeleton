<?php
namespace Star\Middleware;

use Phalcon\Events\Event;
use Star\Util\RabbitWorker;
use Star\Util\Throwable\AbstractRuntimeException;

/**
 * MQ 日志收集中间件
 *
 * @package Star\Middleware
 */
class MQLog
{
    /**
     * 日志名称
     *
     * @var string
     */
    public $name = 'MQ';

    /**
     * 记录 HTTP 请求前置日志
     *
     * @param Event $event
     * @param RabbitWorker $worker
     * @param array $params
     */
    public function beforeHandle(Event $event, RabbitWorker $worker, array $params = [])
    {
        $logger = $worker->getDI()->getShared('service.logger');
        $global = $worker->getDI()->getShared('global');

        $record = [
            'request_id' => $global['requestId'],
            'user_id'   => $global['userId'],
            'label'     => "MQ:before",
            'create_at' => date('Y-m-d H:i:s'),
            'extra'     => [
                'params' => $params
            ]
        ];

        $logger->info("MQ 请求前置日志", $record);
    }

    /**
     * 记录 HTTP 运行时日志
     *
     * @param Event $event
     * @param RabbitWorker $worker
     * @param array $params
     */
    public function handle(Event $event, RabbitWorker $worker, array $params = [])
    {
        $logger = $worker->getDI()->getShared('service.logger');
        $global = $worker->getDI()->getShared('global');

        $record = [
            'request_id' => $global['requestId'],
            'user_id'   => $global['userId'],
            'label'     => "MQ:running",
            'create_at' => date('Y-m-d H:i:s'),
            'extra'     => $params
        ];

        $logger->info("MQ 运行时日志", $record);
    }

    /**
     * 记录 HTTP 结束日志
     *  - 给客户端响应的数据
     *
     * @param Event $event
     * @param RabbitWorker $worker
     * @param array $params
     */
    public function afterHandle(Event $event, RabbitWorker $worker, array $params = [])
    {
        $logger = $worker->getDI()->getShared('service.logger');
        $global = $worker->getDI()->getShared('global');

        $record = [
            'request_id' => $global['requestId'],
            'user_id'   => $global['userId'],
            'label'     => "MQ:after",
            'create_at' => date('Y-m-d H:i:s'),
            'extra'     => [
                // 记录内容使用情况
                'memory_peak_usage' => formatBytes(memory_get_peak_usage(true)),
                'memory_usage'      => formatBytes(memory_get_usage(true)),
            ]
        ];

        $logger->info("MQ 运行时日志", $record);
    }

    /**
     * 记录 HTTP 异常日志
     *
     * @param Event $event
     * @param RabbitWorker $worker
     * @param \Throwable $exception
     */
    public function throwException(Event $event, RabbitWorker $worker, \Throwable $exception)
    {
        $logger = $worker->getDI()->getShared('service.logger');
        $global = $worker->getDI()->getShared('global');

        $record = [
            'request_id'  => $global['requestId'],
            'user_id'   => $global['user_id'],
            'label'      => "MQ:exception",
            'create_at' => date('Y-m-d H:i:s'),
            'extra'     => [
                'message' => $exception->getMessage(),
                'code'    => $exception->getCode(),
                'line'    => $exception->getLine(),
                'file'    => $exception->getFile(),
            ]
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

        $logger->error("MQ Exception", $record);
    }

    /**
     * 记录 HTTP 错误日志
     *
     * @param Event $event
     * @param RabbitWorker $worker
     * @param array $error
     */
    public function throwError(Event $event, RabbitWorker $worker, array $error = [])
    {
        $logger = $worker->getDI()->getShared('service.logger');
        $global = $worker->getDI()->getShared('global');

        $record = [
            'request_id' => $global['requestId'],
            'user_id'   => $global['userId'],
            'label'     => "MQ:error",
            'create_at' => date('Y-m-d H:i:s'),
            'extra'     => [
                'error' => $error,
                // 记录内容使用情况
                'memory_peak_usage' => formatBytes(memory_get_peak_usage(true)),
                'memory_usage'      => formatBytes(memory_get_usage(true)),
            ]
        ];

        $logger->critical("MQ Error", $record);
    }

    /**
     * 记录 HTTP 请求导致进程异常日志
     *
     * @param Event $event
     * @param RabbitWorker $worker
     */
    public function shutdown(Event $event, RabbitWorker $worker)
    {
        $logger = $worker->getDI()->getShared('service.logger');
        $global = $worker->getDI()->getShared('global');

        $record = [
            'request_id'  => $global['requestId'],
            'user_id'   => $global['userId'],
            'label'      => "MQ:shutdown",
            'create_at' => date('Y-m-d H:i:s'),
            'extra'     => [
                'shutdown' => error_get_last(),
                // 记录内容使用情况
                'memory_peak_usage' => formatBytes(memory_get_peak_usage(true)),
                'memory_usage'      => formatBytes(memory_get_usage(true)),
            ]
        ];

        $logger->alert("MQ Shutdown", $record);
    }
}