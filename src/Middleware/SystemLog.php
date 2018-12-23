<?php
namespace Star\Middleware;

use Star\Util\LogCollector;

/**
 * System [框架]日志收集中间件
 *
 * @package Star\Middleware
 */
class SystemLog extends LogCollector
{
    /**
     * 日志名称
     *
     * @var string
     */
    protected $name = 'SYSTEM';
}