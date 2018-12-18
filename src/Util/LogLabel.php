<?php
namespace Star\Util;

/**
 * 日志
 *
 * @package Star\Util
 */
class LogLabel
{
    /**
     * HTTP 请求前置记录 label
     */
    const HTTP_BEFORE = 'http:before';

    /**
     * HTTP 请求运行时记录 label
     */
    const HTTP_RUNNING = 'http:running';

    /**
     * HTTP 请求结束记录 label
     */
    const HTTP_AFTER = 'http:after';

    /**
     * HTTP 请求异常记录 label
     */
    const HTTP_EXCEPTION = 'http:exception';

    /**
     * HTTP 请求错误记录 label
     */
    const HTTP_ERROR = 'http:error';

    /**
     * HTTP 请求导致进程异常 label
     */
    const HTTP_SHUTDOWN = 'http:shutdown';
}
