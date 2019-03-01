<?php
namespace Bee\Db\Redis;

use Bee\Db\ItemInterface;

/**
 * Redis 连接实例
 *
 * @package Bee\Db\Redis
 */
class Item implements ItemInterface
{
    /**
     * @var string
     */
    protected $host = '127.0.0.1';

    /**
     * @var int
     */
    protected $port = 6379;

    /**
     * @var string
     */
    protected $auth = '';

    /**
     * @var int
     */
    protected $timeout = 15;

    /**
     * @var \Redis
     */
    protected $resource;

    /**
     * Item
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (isset($config['host'])) {
            $this->host = $config['host'];
        }
        if (isset($config['port'])) {
            $this->port = $config['port'];
        }
        if (isset($config['auth'])) {
            $this->auth = $config['auth'];
        }
        if (isset($config['timeout'])) {
            $this->timeout = $config['timeout'];
        }

        $this->resource = new \Redis();
    }

    /**
     * 连接数据库
     *
     * @return bool
     */
    public function connect()
    {
        $this->resource->connect($this->host, $this->port, $this->timeout);

        if ($this->auth) {
            $this->resource->auth($this->auth);
        }

        return true;
    }

    /**
     * 关闭数据库连接
     */
    public function close()
    {
        $this->resource->close();
    }

    /**
     * @return \Redis
     */
    public function getResource(): \Redis
    {
        return $this->resource;
    }
}