<?php
namespace Bee\Db;

/**
 * 数据连接对象接口
 *
 * @package Bee\Db
 */
interface LayerInterface
{
    /**
     * 释放数据连接
     *
     * @return mixed
     */
    public function clean();
}
