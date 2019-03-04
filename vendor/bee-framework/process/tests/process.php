<?php
//
//$processes = [];
//
//
//swoole_set_process_name('php-master');
//
//for ($i = 0; $i < 5; $i++) {
//
//    $process = new \Swoole\Process(function (\Swoole\Process $p) {
//        swoole_set_process_name('php-worker ');
//
////        \Swoole\Process::daemon(true);
//
//        while (true) {
//
////            echo $p->pid, PHP_EOL;
//
//            sleep(1);
//
////        $process = new \swoole\process(function (\swoole\process $process) {
////            echo $process->pid , php_eol;
////
////            sleep(1);
////        });
//
////        $process->start();
//        }
//
////    swoole_timer_ick(1000, function () {
////        echo time(), PHP_EOL;
////    });
//
//    });
//
//    $process->start();
//
//    $processes[$i] = $process->pid;
//}
//
////while ($data = \Swoole\Process::wait()) {
////    print_r($data);
////}
//
//
//\Swoole\Process::signal(SIGTERM, function () use ($processes) {
//
//    var_dump($processes);
//
//    foreach ($processes as $process) {
//        \Swoole\Process::kill($process, SIGTERM);
//    }
//});
//
//\Swoole\Process::wait(false);
//
//\Swoole\Process::daemon(true);

//echo $process->pid;

//echo posix_getppid(), PHP_EOL;

//exit();

require __DIR__ . '/../vendor/autoload.php';

class Job1 extends \Bee\Process\Worker
{
    const NAME = 'Job1';

    public function handle(\Swoole\Process $process, $ppid)
    {
        $this->process = $process;
        $this->signal();

        try {
            \Swoole\Event::add(
                $process->pipe,
                function ($pipe) use ($process) {
                    $data = $process->read();
                    print_r(self::NAME . ': ' . $data . PHP_EOL);
//                    $process->write('子进程发来的消息：' . time());

                });

//            \Swoole\Timer::tick(1000, function () {
//                file_put_contents(__DIR__ . '/job1.log', time() . PHP_EOL, 8);
//            });
        } catch (\Throwable $e) {
            print_r($e);
        }
    }
}

class Job2 extends \Bee\Process\Worker
{
    const NAME = 'Job2';

    protected $ha;

    public function handle(\Swoole\Process $process, $ppid)
    {
        $this->process = $process;

        $this->signal();

        \Swoole\Timer::tick(5000, function () {
            throw new \Bee\Process\Exception('故意');
        });
    }
}

class p extends \Bee\Process\Master
{
    public function configure()
    {
        $this->fork([new Job1, 'handle'], Job1::NAME);
        $this->fork([new Job2, 'handle'], Job2::NAME);
    }

    public function fork(callable $callback, $name = 'worker')
    {
        $process = parent::fork($callback, $name);

//        \Swoole\Timer::tick(2000, function ($id) use ($process) {
//            $process->write('来自父进程的数据：' . time());
//        });

        $this->processes[$name] = $process;

        return $process;
    }
}

$di = new Phalcon\Di;

$process = new p([
    'name' => 'Bee',
    'logFile' => __DIR__ . '/bee.log'
]);

//$process->reload();
//$process->restart();
$process->stop();
//$process->shutdown();
$process->status();
