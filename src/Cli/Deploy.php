<?php
namespace Star\Cli;

use Bee\Cli\Command;
use Phalcon\Di;

/**
 * 应用部署服务命令
 *
 * @package Ant\Cli\Command
 */
class Deploy extends Command
{
    /**
     * 命令名称
     *
     * @var string
     */
    protected $name = 'deploy';

    /**
     * 命令说明
     *
     * @var string
     */
    protected $desc = '应用部署，根部不同获取运行所需的配置';

    /**
     * 注册帮助显示的信息
     */
    public function initShowHelp()
    {
        // 注册参数信息
        $this
            ->argument('<action>', '操作可选值：[dev|test|pre|pub]')
        ;

        // 注册使用示例信息
        $this->usage(
            $this->writer()->colorizer()->colors(''
                . '<bold>./server deploy</end> <line>dev</end> '
                . '<comment>拉取开发环境配置文件</end><eol/>'
            )
        );
    }

    /**
     * 命令执行体
     *
     * @param string $action
     */
    public function execute($action = 'dev')
    {
        $deploy = Di::getDefault()->getShared('service.deploy');
        $deploy->run($action);
    }
}
