<?php
namespace Star\Middleware;


use Star\Util\LogCollector;

/**
 * HTTP 日志收集中间件
 *
 * @package Star\Middleware
 */
class HttpLog extends LogCollector
{
    /**
     * 日志名称
     *
     * @var string
     */
    protected $name = 'HTTP';
}
