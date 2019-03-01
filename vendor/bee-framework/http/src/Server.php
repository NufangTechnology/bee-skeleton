<?php
namespace Bee\Http;

use Ahc\Cli\Output\Writer;
use Swoole\Http\Server as SwooleHttpServer;
use Swoole\Process;
use Swoole\Server\Task;

/**
 * Http Server
 *
 * @package Bee\Http
 */
abstract class Server implements ServerInterface
{
    /**
     * @var string
     */
    private $host = '127.0.0.1';

    /**
     * @var int
     */
    private $port = 9527;

    /**
     * @var array
     */
    private $option = [
        'pid_file'         => '/tmp/bee-http.pid',
        'log_file'         => '/tmp/bee-http_server.log',
        'worker_num'       => 4,
        'task_worker_num'  => 4,
    ];

    /**
     * @var string
     */
    protected $name = 'bee-http';

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
    protected $pidFile;

    /**
     * @var SwooleHttpServer
     */
    protected $swoole;

    /**
     * @var Writer
     */
    protected $output;

    /**
     * Server constructor.
     *
     * @param array $runtime
     */
    public function __construct(array $runtime)
    {
        // Server名称
        if (isset($runtime['name'])) {
            $this->name = $runtime['name'];
        }

        // 服务绑定ip
        if (isset($runtime['host'])) {
            $this->host = $runtime['host'];
        }

        // 服务端口号
        if (isset($runtime['port'])) {
            $this->port = $runtime['port'];
        }

        // server配置
        if (isset($runtime['option'])) {
            $this->option = $runtime['option'];
        }

        // pid文件
        $this->pidFile = $this->option['pid_file'];
        // 控制台信息
        $this->output  = new Writer();
    }

    /**
     * 注册回调方法
     *
     * @return $this
     */
    protected function registerCallback()
    {
        $handles = get_class_methods($this);

        foreach ($handles as $value) {
            if ('on' == substr($value, 0, 2)) {
                $this->swoole->on(lcfirst(substr($value, 2)), [$this, $value]);
            }
        }

        return $this;
    }

    /**
     * 启动
     */
    public function start()
    {
        if ($this->isRunning()) {
            $this->output->warn("无效操作，服务已经在[{$this->host}:{$this->port}]运行！");
            return;
        }

        // 设置进程名称
        swoole_set_process_name($this->name . ':master');
        // 启动Http服务
        $this->swoole = new SwooleHttpServer($this->host, $this->port);
        $this->swoole->set($this->option);
        $this->registerCallback();
        $this->swoole->start();
    }

    /**
     * 重新worker进程
     *  - 该操作只能重新载入Worker进程启动后加载的PHP文件，
     */
    public function reload()
    {
        if ($this->isRunning()) {
            Process::kill($this->pid(), SIGUSR1);
        } else {
            $this->output->warn('未找到运行中的服务', true);
        }
    }

    /**
     * 重启服务
     *
     * @param bool $force
     * @throws Exception
     */
    public function restart($force = false)
    {
        if ($this->isRunning()) {
            if ($force) {
                $this->shutdown();
            } else {
                $this->stop();
            }
        }

        $this->start();
    }

    /**
     * 停止服务（平滑停止）
     */
    public function stop()
    {
        if ($this->isRunning() == false) {
            $this->output->warn('未找到运行中的服务', true);
            return;
        }

        Process::kill($this->pid(), SIGTERM);

        while (true) {
            if ($this->isRunning() == false) {
                break;
            }
        }
    }

    /**
     * 强制退出主进程及子进程
     *
     * @throws Exception
     */
    public function shutdown()
    {
        // 强制杀死进程
        exec("ps -ef | grep {$this->name} | grep -vE 'grep|watcher' | cut -c 9-15 | xargs kill -s 9");

        while (true) {
            if ($this->isRunning() == false) {
                // 删除进程pid文件
                $this->deletePidFile();
                break;
            }
        }
    }

    /**
     * 获取服务进程状态
     */
    public function status()
    {
        if (!$this->isRunning()) {
            $this->output->warn('没有运行中的服务', true);
            return;
        }

        $pid = $this->pid();

        // 根据主进程ID获取相关进程（子进程）运行信息
        exec("ps -A -o user,pid,ppid,pmem,pcpu,stat,comm,cmd | grep -E '{$pid}|%MEM|{$this->name}'", $result);
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
     * 检查服务是否处于运行中
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
     * 获取去当前进程ID
     *
     * @return int
     */
    public function pid() : int
    {
        if (!$this->isRunning()) {
            return 0;
        }

        if (!empty($this->pid)) {
            return $this->pid;
        }

        $pid = @file_get_contents($this->pidFile);

        return intval($pid);
    }

    /**
     * 删除进程pid文件
     *
     * @throws Exception
     */
    private function deletePidFile()
    {
        if (!is_file($this->pidFile)) {
            return;
        }

        @unlink($this->pidFile);

        if (is_file($this->pidFile)) {
            throw new Exception('进程pid文件删除失败');
        }
    }

    /**
     * 信号处理
     */
    public function signalHandle()
    {
        // 获取运行状态
        Process::signal(SIGUSR2, [$this, 'status']);
    }

    public function onTask(SwooleHttpServer $server, Task $task) {}

    public function onFinish(SwooleHttpServer $server, $taskId, $data) {}

    public function onShutdown(SwooleHttpServer $server) {}

    public function onWorkerExit(SwooleHttpServer $server, $workerId) {}
}
