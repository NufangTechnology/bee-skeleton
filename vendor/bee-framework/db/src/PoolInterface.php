<?php
namespace Bee\Db;

/**
 * Interface PoolInterface
 *
 * @package Bee\Db
 */
interface PoolInterface
{
    /**
     * 放回数据连接实例
     *
     * @param \Bee\Db\MySQL\Item|\Bee\Db\Redis\Item $item
     * @return bool
     */
    public function put($item);

    /**
     * 获取数据连接实例
     *
     * @return \Bee\Db\MySQL\Item|\Swoole\Coroutine\Redis
     */
    public function get();

    /**
     * 清空数据库连接池（释放数据连接）
     *
     * @return mixed
     */
    public function clean();
}
