<?php
namespace Bee\Cli;

use Ahc\Cli\Application;

/**
 * 命令行引导工具
 *
 * @package Ant
 */
class Console
{
    /**
     * @var string
     */
    private $name = 'Bee';

    /**
     * @var string
     */
    private $version = '0.3.2';

    /**
     * @var Application
     */
    private $app;

    /**
     * @var string
     */
    private $logo = "
        ██████╗ ███████╗███████╗
        ██╔══██╗██╔════╝██╔════╝
        ██████╔╝█████╗  █████╗  
        ██╔══██╗██╔══╝  ██╔══╝  
        ██████╔╝███████╗███████╗
        ╚═════╝ ╚══════╝╚══════╝
==========================================
A resident micro-service framework for PHP
    ";

    /**
     * Cli constructor.
     *
     * @param array $commands
     * @throws Exception
     */
    public function __construct($commands = [])
    {
        $this->app = new Application($this->name, $this->version);

        // 加载命令
        $this->loadCommand($commands ?? []);
    }

    /**
     * 加载命令行对象
     *
     * @param array $commands
     * @throws Exception
     */
    private function loadCommand(array $commands)
    {
        foreach ($commands as $command) {

            if (empty($command['class'])) {
                throw new Exception('class参数不能为空');
            }

            // 命令执行服务类
            $class  = $command['class'];
            // 配置
            $config = $command['config'] ?? [];
            // 命令别名
            $alias  = $command['alias'] ?? '';

            // 注入
            $this->app->add(new $class($this->app, $config), $alias);
        }
    }

    /**
     * 执行命令行
     *
     * @param array $argv
     */
    public function launch($argv = [])
    {
        $this->app->logo($this->logo);
        $this->app->handle($argv);
    }
}
