<?php
namespace Bee\Cli;

use Ahc\Cli\Application;
use Ahc\Cli\Input\Command as AhcCommand;

/**
 * 命名基类
 *
 * @package Ant\Cli
 */
abstract class Command extends AhcCommand implements CommandInterface
{
    /**
     * @var Application
     */
    protected $cli;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $name = 'default';

    /**
     * @var string
     */
    protected $desc = 'default';

    /**
     * Command constructor.
     *
     * @param Application $app
     * @param array $config
     */
    public function __construct($app, $config = [])
    {
        parent::__construct($this->name, $this->desc);

        // 注册帮助信息
        $this->initShowHelp();

        // 命令执行方法
        $this->_action = [$this, 'execute'];

        $this->cli    = $app;
        $this->config = $config;
    }
}
