<?php
namespace Star\Cli;

use Bee\Cli\Command;
use Phalcon\Di;

/**
 * Http
 *
 * @package Eye\Cli
 */
class Http extends Command
{
    /**
     * 命令名称
     *
     * @var string
     */
    protected $name = 'http';

    /**
     * 命令说明
     *
     * @var string
     */
    protected $desc = 'HTTP服务相关操作';

    /**
     * 初始化命令帮助信息
     */
    public function initShowHelp()
    {
        // 注册参数信息
        $this
            ->argument('<action>', '操作可选值：[start|reload|restart|stop|shutdown]')
            ->argument('status', '查看服务运行状态')
        ;

        // 注册选项信息
        $this
            ->option('-f --force', '强制执行')
        ;

        // 注册使用示例信息
        $this->usage(
            $this->writer()->colorizer()->colors(''
                . '<bold>server http</end> <line>start</end> '
                . '<comment>启动HTTP者服务</end><eol/>'
            )
        );
    }

    /**
     * 命令执行体
     *
     * @param string $action
     * @param bool $force
     */
    public function execute($action = 'start', $force = false)
    {
        // 获取http服务
        $http = Di::getDefault()->getShared('service.http');

        switch ($action) {
            case 'start':
                $http->start();
                break;

            case 'stop':
                $http->stop();
                break;

            case 'restart':
                $http->restart($force);
                break;

            case 'shutdown':
                $http->shutdown();
                break;

            case 'status':
                $http->status();
                break;
        }
    }
}
