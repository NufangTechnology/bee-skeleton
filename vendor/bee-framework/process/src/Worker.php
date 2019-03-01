<?php
namespace Bee\Process;

use Swoole\Process;
use Swoole\Timer;

/**
 * Worker
 * @package Bee\Process
 */
abstract class Worker
{
    /**
     * 当前进程是否空闲
     *
     * @var bool
     */
    protected $idle = true;

    /**
     * @var Process
     */
    protected $process;

    /**
     * 父进程pid
     *
     * @var integer
     */
    protected $ppid;

    /**
     * 注册子进程信号处理毁掉
     */
    protected function signal()
    {
        // 结束所有子进程
        Process::signal(SIGUSR1, function () {
            if ($this->idle) {
                $this->workerExit();
            }
        });
    }

    /**
     * 检查父进程运行状态（父进程退出，子进程自动退出）
     */
    protected function checkMaster()
    {
        if (!$this->masterStatus()) {
            $this->workerExit();
        }
    }

    /**
     * 获取主进程状态
     *
     * @return mixed
     */
    protected function masterStatus()
    {
        return Process::kill($this->ppid, SIG_DFL);
    }

    /**
     * 设置定制器检查主进程状态
     */
    protected function setTickCheckAlive()
    {
        Timer::tick(10000, function ($id) {
            // 主进程已结束并且当前进程处于空闲状态
            // 退出当前进程
            if (!$this->masterStatus() && !$this->idle) {
                $this->workerExit();
                // 清除当前定时器
                Timer::clear($id);
            }
        });
    }

    /**
     * 使用管道发送数据
     *  - 进程间
     *
     * @param $target
     * @param array $data
     */
    protected function send($target, array $data = [])
    {
        $this->process->write(igbinary_serialize(
            [
                'target' => $target,
                'data' => $data
            ]
        ));
    }

    /**
     * 退出当前进程
     */
    protected function exit()
    {
        $this->process->exit(1);
    }

    /**
     * 进程退出时回调方法
     *  - 资源释放
     */
    protected function workerExit()
    {
        $this->exit();
    }

    /**
     * Worker业务代码
     *
     * @param Process $process 当前进程信息
     * @param int $ppid 父进程pid
     */
    abstract public function handle(Process $process, $ppid);
}
