<?php
namespace Star\Console;

use Bee\Cli\Command;
use Bee\Di\Container;

/**
 * Job
 *
 * @package Star\Console
 */
class Job extends Command
{
    /**
     * 命令名称
     *
     * @var string
     */
    protected $name = 'job';

    /**
     * 命令说明
     *
     * @var string
     */
    protected $desc = 'Job服务相关操作';

    /**
     * 初始化命令帮助信息
     */
    public function initShowHelp()
    {
        // 注册参数信息
        $this
            ->argument('<action>', '操作可选值：[start|status|restart|stop|shutdown]')
            ->argument('status', '查看服务运行状态')
        ;

        // 注册选项信息
        $this
            ->option('-f --force', '强制执行')
        ;

        // 注册使用示例信息
        $this->usage(
            $this->writer()->colorizer()->colors(''
                . '<bold>bee job</end> <line>start</end> '
                . '<comment>启动job者服务</end><eol/>'
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
        $service = Container::getDefault()->getShared('service.job');

        switch ($action) {
            case 'start':
                $service->start();
                break;

            case 'stop':
                $service->stop();
                break;

            case 'restart':
                $service->restart($force);
                break;

            case 'shutdown':
                $service->shutdown();
                break;

            case 'status':
                $service->status();
                break;
        }
    }
}
