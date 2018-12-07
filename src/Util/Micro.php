<?php
namespace Star\Util;

use Phalcon\Config;
use Swoole\Server;

/**
 * Micro
 *
 * @package Eye\Util
 *
 * @property Config $global
 * @property Server $server
 */
class Micro extends \Phalcon\Mvc\Micro
{
    /**
     * @var int
     */
    protected $startTime = 0;

    /**
     * 执行业务处理
     *
     * @param string $uri
     */
    public function handle($uri = null)
    {
        // 业务开始处理时间
        $this->startTime = microtime(true);

        try {

            // 执行业务处理
            $result = parent::handle($uri);

            // 记录handle日志
            $this->eventsManager->fire('log:afterHandleRequest', $this, $result);
            // 输出相应内容
            $this->eventsManager->fire('micro:afterHandleRequest', $this, $result);

        } catch (\Throwable $e) {
            $this->eventsManager->fire("log:handleThrowable", $this, $e);
            // 记录handle日志
            $this->eventsManager->fire('micro:afterHandleException', $this, $e);
        }
    }

    /**
     * 获取请求处理时间
     *
     * @return int
     */
    public function startTime() : int
    {
        return $this->startTime;
    }

    /**
     * 计算应用处理花费时间
     *
     * @return int|mixed
     */
    public function expendTime()
    {
        return (microtime(true)) - $this->startTime;
    }
}