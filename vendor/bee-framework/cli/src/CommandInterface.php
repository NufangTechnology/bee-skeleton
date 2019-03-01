<?php
namespace Bee\Cli;

/**
 * Interface CommandInterface
 *
 * @package Ahc\Cli
 */
interface CommandInterface
{
    /**
     * 初始化命令帮助信息
     */
    public function initShowHelp();

    /**
     * 命令执行体
     *
     * @param string $action
     */
    public function execute($action = 'start');
}
