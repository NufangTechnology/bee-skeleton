<?php
namespace Star\Job;

use Swoole\Process;
use Bee\Process\Worker;

/**
 * Demo Job
 * @package Star\Job
 */
class Demo extends Worker
{
    /**
     * @param Process $process
     * @param int $ppid
     */
    public function handle(Process $process, $ppid)
    {
    }
}