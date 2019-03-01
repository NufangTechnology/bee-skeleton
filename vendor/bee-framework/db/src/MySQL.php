<?php
namespace Bee\Db;

use Bee\Db\MySQL\Pool;
use Bee\Db\MySQL\Item;

/**
 * MySQL 连接器
 *
 * @package Bee\Db
 */
class MySQL extends Layer
{
    /**
     * 初始化主节点连接池
     *
     * @param array $config
     */
    protected function initMasterPool(array $config)
    {
        $pool = [];

        if (isset($config['host'])) {
            $config = [$config];
        }

        foreach ($config as $item) {
            $size = $item['pool_size'] ?? 1;

            while ($size--) {
                $pool[] = new Item($item);
            }
        }

        // 创建连接池 chan
        $this->masterPool = new Pool(count($pool));
        // 将生成好的连接放入连接池
        foreach ($pool as $item) {
            $this->masterPool->put($item);
        }
    }

    /**
     * 初始化从节点连接池
     *
     * @param array $config
     */
    protected function initSlavePool(array $config)
    {
        $pool = [];

        if (isset($config['host'])) {
            $config = [$config];
        }

        foreach ($config as $item) {
            $size = $item['pool_size'] ?? 1;

            while ($size--) {
                $pool[] = new Item($item);
            }
        }

        // 创建连接池 chan
        $this->slavePool = new Pool(count($pool));
        // 将生成好的连接放入连接池
        foreach ($pool as $item) {
            $this->slavePool->put($item);
        }
    }

    /**
     * 发送待执行 sql
     *
     * @param string $sql
     * @param array $params
     * @param Item $item
     * @param float $timeout
     * @return array
     * @throws MySQL\Exception
     */
    protected function send(string $sql, array $params,  Item $item, float $timeout)
    {
        return $item->query($sql, $params, $timeout);
    }

    /**
     * 数据库主节点 sql 操作
     *
     * @param string $sql
     * @param array $params
     * @param float $timeout
     * @return array
     * @throws \Exception
     */
    public function master(string $sql, array $params = [], float $timeout = 0)
    {
        // 取一个连接进行业务操作
        $item = $this->masterPool->get();

        try {
            // 执行数据库业务
            $result = $this->send($sql, $params, $item, $timeout);
            // 业务处理结束，连接放回连接池
            $this->masterPool->put($item);

            return $result;
        } catch (\Exception $e) {
            // 业务处理结束，连接放回连接池(防止连接池连接丢失)
            $this->masterPool->put($item);

            throw $e;
        }
    }

    /**
     * 数据库从节点 sql 操作
     *
     * @param string $sql
     * @param array $params
     * @param float $timeout
     * @return array
     * @throws \Exception
     */
    public function slave(string $sql, array $params = [], float $timeout = 0)
    {
        // 取一个连接进行业务操作
        $item   = $this->slavePool->get();

        try {
            // 执行数据库业务
            $result = $this->send($sql, $params, $item, $timeout);
            // 业务处理结束，连接放回连接池
            $this->slavePool->put($item);

            return $result;
        } catch (\Exception $e) {
            // 业务处理结束，连接放回连接池(防止连接池连接丢失)
            $this->slavePool->put($item);

            throw $e;
        }
    }

    /**
     * sql 插入操作
     *
     * @param string $sql
     * @param array $params
     * @param float $timeout
     * @return array
     * @throws \Exception
     */
    public function insert(string $sql, array $params = [], float $timeout = 0)
    {
        return $this->master($sql, $params, $timeout);
    }

    /**
     * sql 查询操作
     *
     * @param string $sql
     * @param array $params
     * @param float $timeout
     * @return array
     * @throws \Exception
     */
    public function select(string $sql, array $params = [], float $timeout = 0)
    {
        return $this->slave($sql, $params, $timeout);
    }

    /**
     * sql 更新操作
     *
     * @param string $sql
     * @param array $params
     * @param float $timeout
     * @return array
     * @throws \Exception
     */
    public function update(string $sql, array $params = [], float $timeout = 0)
    {
        return $this->master($sql, $params, $timeout);
    }

    /**
     * sql 删除操作
     *
     * @param string $sql
     * @param array $params
     * @param float $timeout
     * @return array
     * @throws \Exception
     */
    public function delete(string $sql, array $params = [], float $timeout = 0)
    {
        return $this->master($sql, $params, $timeout);
    }

    /**
     * 释放数据连接
     */
    public function clean()
    {
        $this->masterPool->clean();
        $this->slavePool->clean();
    }
}