<?php
namespace Star\Job;

use Bee\Di\Container as Di;

/**
 * 多进程管理器
 *
 * @package Star\Job
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
            // 创建子进程
            $this->fork(new $worker['class'], $worker['name']);
        }
    }
}
