<?php
namespace Bee\Db;

/**
 * Interface ItemInterface
 *
 * @package Bee\Db
 */
interface ItemInterface
{
    /**
     * 连接数据库
     *
     * @return mixed
     */
    public function connect();

    /**
     * 关闭数据库连接
     */
    public function close();
}
