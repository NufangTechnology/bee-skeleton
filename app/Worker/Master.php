<?php
namespace Star\Worker;

use Bee\Di\Container as Di;

/**
 * 多进程管理器
 *
 * @package Star\Worker
 */
class Master extends \Bee\Process\Master
{
    /**
     * 主进程业务配置
     */
    public function configure()
    {
        // 获取 worker 配置
        $workers = Di::getDefault()->getShared('config.worker');
        foreach ($workers as $worker) {
            // worker实例化
            $class = new $worker['class'];
            // 创建子进程
            $this->fork([$class, 'handle'], $worker['name']);
        }
    }
}
