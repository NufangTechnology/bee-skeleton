<?php
namespace Star\Middleware;

use Star\Util\LogCollector;

/**
 * MQ 日志收集中间件
 *
 * @package Star\Middleware
 */
class MQLog extends LogCollector
{
    /**
     * 日志名称
     *
     * @var string
     */
    protected $name = 'MQ';
}