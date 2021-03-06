<?php
namespace Star\Util;

use Bee\Di\Container;
use Bee\Error;
use Bee\Exception;
use Bee\Http\Context;

/**
 * 记录异常信息
 *
 * @package Star\Util
 */
class ThrowExceptionHandler
{
    /**
     * @var \Bee\Logger\Adapter\SeasLog
     */
    static public $logger;

    /**
     * http 接口请求异常记录
     *
     * @param Exception $e
     * @param Context $context
     * @return string
     */
    public static function http(Exception $e, Context $context): string
    {
        // 合并异常与上下文数据
        // http 异常日志提取时需要上下文请求信息
        $data         = array_merge($e->toArray(), $context->toArray());
        // 拼接其他日志数据
        $data['type'] = 'http';

        // 记录日异常志数据
        self::report($e->getLevel(), $data);

        return json_encode(
            [
                'result' => false,
                'code'   => $e->getCode(),
                'msg'    => $e->getMessage(),
                'info'   => $e->getData(),
            ],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * websocket 异常日志数据记录
     *
     * @param Exception $e
     */
    public static function websocket(Exception $e)
    {
        $data         = $e->toArray();
        // 拼接其他日志数据
        $data['type'] = 'websocket';

        self::report($e->getLevel(), $data);
    }

    /**
     * task 异常日志数据记录
     *
     * @param Exception $e
     */
    public static function task(Exception $e)
    {
        $data         = $e->toArray();
        // 拼接其他日志数据
        $data['type'] = 'task';

        self::report($e->getLevel(), $data);
    }

    /**
     * worker / job 异常日志记录
     *
     * @param Exception $e
     */
    public static function job(Exception $e)
    {
        $data         = $e->toArray();
        // 拼接其他日志数据
        $data['type'] = 'job';

        self::report($e->getLevel(), $data);
    }

    /**
     * 错误异常日志记录
     *
     * @param Error $e
     */
    public static function error(Error $e)
    {
        $data         = $e->toArray();
        // 拼接其他日志数据
        $data['type'] = 'error';

        self::report($e->getLevel(), $data);
    }

    /**
     * 未捕获异常日志记录
     *
     * @param \Throwable $throwable
     * @return string
     */
    public static function uncaught(\Throwable $throwable): string
    {
        $trace = $throwable->getTrace();

        $data  = [
            'type'    => 'uncaught',
            'message' => $throwable->getMessage(),
            'code'    => $throwable->getCode(),
            'line'    => $throwable->getLine(),
            'class'   => get_class($throwable),
            'file'    => $throwable->getFile(),
            'args'    => $trace[0] ?? [],
        ];

        self::report('critical', $data);

        return '{"result":false,"code":500000,"msg":"服务异常，请稍后重试"}';
    }

    /**
     * 执行日志记录
     *
     * @param string $level
     * @param array $data
     */
    public static function report(string $level, array $data)
    {
        if (empty(self::$logger)) {
            self::$logger  = Container::getDefault()->getShared('service.logger');
        }

        // 拼接应用名称
        $data['app_name']  = APP_NAME;

        // 执行日志记录
        self::$logger->log($level, json_encode($data, JSON_UNESCAPED_UNICODE));
    }
}
