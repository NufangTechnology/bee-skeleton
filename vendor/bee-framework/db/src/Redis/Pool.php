<?php
namespace Bee\Db\Redis;

use Bee\Db\PoolInterface;
use Swoole\Coroutine\Channel;

/**
 * Redis 连接池
 *
 * @package Bee\Db\Redis
 */
class Pool implements PoolInterface
{
    /**
     * channel 获取对象超时时间
     *
     * @var float
     */
    protected $timeout = 40;

    /**
     * @var Channel
     */
    protected $pool;

    /**
     * Pool constructor.
     *
     * @param int $size
     */
    public function __construct(int $size)
    {
        $this->pool = new Channel($size);
    }

    /**
     * @param Item $item
     * @return bool
     */
    public function put($item)
    {
        return $this->pool->push($item);
    }

    /**
     * @return \Swoole\Coroutine\Redis
     * @throws Exception
     */
    public function get()
    {
        $item = $this->pool->pop($this->timeout);

        if ($item === false) {
            throw new Exception('Get redis connection instance timeout, pool: ' . $this->getLength());
        }

        return $item;
    }

    /**
     * @return int
     */
    public function getLength() : int
    {
        return $this->pool->length();
    }

    /**
     * 清空数据库连接池（释放数据连接）
     */
    public function clean()
    {
        while ($item = $this->pool->pop($this->timeout)) {
            $item->close();
        }
    }
}
