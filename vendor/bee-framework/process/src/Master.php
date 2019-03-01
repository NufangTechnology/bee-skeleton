<?php
namespace Bee\Process;

use Ahc\Cli\Output\Writer;
use Swoole\Process;
use Swoole\Timer;

/**
 * Master
 *
 * @package Ant\Process
 */
abstract class Master
{
    /**
     * 主进程ID
     *
     * @var integer
     */
    protected $pid;

    /**
     * 进程pid文件
     *
     * @var string
     */
    protected $pidFile = '/tmp/bee-service.pid';

    /**
     * @var string
     */
    protected $logFile = '/tmp/bee-service.log';

    /**
     * 主进程名称
     *
     * @var string
     */
    protected $prefix = 'bee';

    /**
     * 是否以守护模式运行
     *
     * @var bool
     */
    protected $daemon = true;

    /**
     * 子进程集
     *
     * @var Process[]
     */
    protected $processes = [];

    /**
     * 子进程脚本配置
     *
     * @var array
     */
    protected $configs = [];

    /**
     * @var bool
     */
    protected $redirect = false;

    /**
     * @var bool
     */
    protected $pipe = true;

    /**
     * @var Writer
     */
    protected $output;

    /**
     * @var bool
     */
    protected $waitExit = false;

    /**
     * @var int
     */
    protected $childTimerId;

    /**
     * constructor
     *
     * @param array $runtime
     */
    public function __construct(array $runtime)
    {
        if (isset($runtime['name'])) {
            $this->prefix = $runtime['name'];
        }
        if (isset($runtime['pidFile'])) {
            $this->pidFile = $runtime['pidFile'];
        }
        if (isset($runtime['daemon'])) {
            $this->daemon = boolval($runtime['daemon']);
        }
        if (isset($runtime['pipe'])) {
            $this->pipe = boolval($runtime['pipe']);
        }
        if (isset($runtime['redirect'])) {
            $this->redirect = $runtime['redirect'];
        }
        if (isset($runtime['logFile'])) {
            $this->logFile = $runtime['logFile'];
        }

        $this->output = new Writer;
    }

    /**
     * 启用进程及相关业务
     */
    public function start()
    {
        if ($this->isRunning()) {
            $this->output->warn('应用启动失败，服务已在运行中', true);
            return;
        }

        if ($this->daemon) {
            Process::daemon(true);
        }

        // 记录进程信息
        $this->log('', '进程组启动', 'group');

        // 标记主进程状态
        $this->waitExit = false;
        // 注册信号处理函数
        $this->signal();
        // 设置当前进程名称
        $this->name("master", true);
        // 获取当前进程ID
        $this->pid = getmypid();
        // 执行自定义启动事件
        $this->configure();
        // 写入pid文件
        $this->createPidFile();
        // 定时检查子进程状态
        $this->checkChildAlive();
        // 输出进程状态
        $this->status();

        // 记录进程信息
        $this->log($this->pid, '主进程启动', 'master');
    }

    /**
     * 重新worker进程
     *  - 该操作只能重新载入Worker进程启动后加载的PHP文件，
     */
    public function reload()
    {
        if ($this->isRunning() == false) {
            $this->output->warn('未找到运行中的服务', true);
            return;
        }

        // 记录进程信息
        $this->log('', 'worker平滑重启');
        // 发送信号重启子进程
        Process::kill($this->pid(), SIGUSR1);
    }

    /**
     * 重启服务
     *
     * @param bool $force 强制重启
     */
    public function restart($force = false)
    {
        if ($force) {
            $this->shutdown();
        } else {
            $this->stop();
        }

        // 记录进程信息
        $this->log('', '服务平滑重启');

        $this->start();
    }

    /**
     * 平滑停止
     */
    public function stop()
    {
        if ($this->isRunning() == false) {
            $this->output->warn('未找到运行中的服务', true);
            return;
        }

        // 强制停止主进程（子进程会自动检测退出）
        Process::kill($this->pid(), SIGTERM);
        // 等待进程退出，删除进程PID文件
        while (true) {
            if ($this->isRunning() == false) {
                // 删除进程pid
                $this->deletePidFile();
                break;
            }
        }

        // 记录进程信息
        $this->log('', '服务平滑停止', true);
    }

    /**
     * 强制退出主进程及子进程
     */
    public function shutdown()
    {
        // 强制杀死进程
        exec("ps -ef | grep {$this->prefix} | grep -vE 'grep|watcher' | cut -c 9-15 | xargs kill -s 9");
        // 等待主进程退出删除进程PID文件
        while (true) {
            if ($this->isRunning() == false) {
                // 删除进程pid文件
                $this->deletePidFile();
                break;
            }
        }

        // 记录进程信息
        $this->log($this->pid, '进程组退出', 'master');
    }

    /**
     * 获取应用进程状态信息
     */
    public function status()
    {
        if (!$this->isRunning()) {
            $this->output->warn('没有运行中的服务', true);
            return;
        }

        $pid = $this->pid();

        // 根据主进程ID获取相关进程（子进程）运行信息
        exec("ps -A -o user,pid,ppid,pmem,pcpu,stat,comm,cmd | grep -E '{$pid}|%MEM|{$this->prefix}:watcher'", $result);
        // 删除最后两行（shell指令自身）
        array_pop($result);
        array_pop($result);
        // 提取并输出菜单栏
        $this->output->ok(array_shift($result), true);
        // 输出进程状态明细
        foreach ($result as $line) {
            $this->output->write($line, true);
        }
    }

    /**
     * 获取当主进程pid
     *
     * @return int
     */
    public function pid() : int
    {
        if ($this->isRunning()) {
            if (!empty($this->pid)) {
                return $this->pid;
            }

            $pid = @file_get_contents($this->pidFile);

            return intval($pid);
        }

        return 0;
    }

    /**
     * 检查主进程是否运行
     *
     * @return bool
     */
    public function isRunning()
    {
        if ($this->pid) {
            return true;
        }

        if (!is_file($this->pidFile)) {
            return false;
        }

        $pid = @file_get_contents($this->pidFile);

        if (empty($pid)) {
            return false;
        }

        return Process::kill(intval($pid), SIG_DFL);
    }

    /**
     * 创建子进程
     *
     * @param callable $callback
     * @param string $name
     * @return Process
     */
    public function fork(callable $callback, $name = 'worker')
    {
        // 实例化进程
        $process = new Process(
            function (Process $process) use ($callback, $name) {
                // 设置进程名称
                $this->name($name, true);
                // 获取进程名称
                $this->log($process->pid, '子进程启动', $name);
                // 执行worker业务代码
                call_user_func($callback, $process, $this->pid);
            },
            $this->redirect,
            $this->pipe
        );
        // 启动进程
        $pid = $process->start();

        // 保存子进程类handler
        $this->configs[$pid] = ['callback' => $callback, 'name' => $name];

        return $process;
    }

    /**
     * 定时检查子进程状态
     *  - 每10秒检查子进程状态，如果子进程退出，重新拉起子进程
     */
    protected function checkChildAlive()
    {
        $id = Timer::tick(10000, function () {
            foreach ($this->configs as $pid => $config) {
                if (!Process::kill($pid, SIG_DFL)) {
                    $this->bootChild($pid);
                }
            }
        });

        $this->childTimerId = $id;
    }

    /**
     * 重启子进程
     *
     * @param int $pid
     */
    protected function bootChild($pid)
    {
        // 进程信息
        if (!isset($this->configs[$pid])) {
            return;
        }
        // 获取worker（子进程）信息
        $worker = $this->configs[$pid];

        // 记录进程信息
        $this->log($pid, '子进程退出', $worker['name']);

        // waitExit = true
        // 意味着主进程准备退出，改状态下不再拉起子进程
        if ($this->waitExit) {
            return;
        }

        // 启动新的子进程
        $this->fork($worker['callback'], $worker['name']);
        // 删除相关配置
        unset($this->configs[$pid]);
    }

    /**
     * 平滑退出子进程
     */
    protected function exitChild()
    {
        // 给子进程发送结束信号
        foreach ($this->configs as $pid => $worker) {
            Process::kill($pid, SIGUSR1);
            // 记录进程信息
            $this->log($pid, '子进程退出', $worker['name']);
        }
    }

    /**
     * 注册信号处理
     */
    protected function signal()
    {
        // 强制退出（系统信号）
        Process::signal(SIGKILL, [$this, 'shutdown']);

        // 平滑重启
        Process::signal(SIGTERM, function () {
            // 标记主进程状态为准备退出
            $this->waitExit = true;
            // 平滑退出子进程
            $this->exitChild();
            // 记录进程信息
            $this->log($this->pid, '主进程退出', 'master');
            // 退出当前进程
            exit(1);
        });

        // 平滑重启子进程
        Process::signal(SIGUSR1, function () {
            // 平滑退出子进程
            $this->exitChild();
        });

        // 获取运行状态
        Process::signal(SIGUSR2, [$this, 'status']);

        // 子进程退出，重新创建子进程
        Process::signal(SIGCHLD, function () {
            // 接受子进程数据
            while ($data = Process::wait(false)) {
                // 进程ID
                if (!isset($data['pid'])) {
                    break;
                }
                // 重启子进程
                $this->bootChild($data['pid']);
            }
        });
    }

    /**
     * 创建pid文件
     */
    private function createPidFile()
    {
        @file_put_contents($this->pidFile, $this->pid);

        // 检查pid文件是否写入成功
        if (!is_file($this->pidFile)) {
            $this->output->error('进程pid文件创建失败');
        }
    }

    /**
     * 删除进程pid文件
     */
    private function deletePidFile()
    {
        if (!is_file($this->pidFile)) {
            return;
        }

        @unlink($this->pidFile);
    }

    /**
     * 设置进程名称
     *
     * @param string $name
     * @param bool $set
     * @return string
     */
    protected function name($name = 'child', $set = false)
    {
        $processName = "{$this->prefix}:{$name}";

        // 设置进程名称
        if ($set) {
            swoole_set_process_name($processName);
        }

        return $processName;
    }

    /**
     * 记录进程信息
     * @param int $pid
     * @param string $desc
     * @param string $name
     */
    protected function log($pid, $desc, $name = '')
    {
        $processName = $this->name($name);
        $date        = date('Y-m-d H:i:s');
        $content     = "{$desc}: {$date} [{$pid} {$processName}]" . PHP_EOL;

        @file_put_contents($this->logFile, $content, FILE_APPEND);
    }

    /**
     * 主进程业务配置
     */
    abstract public function configure();
}
