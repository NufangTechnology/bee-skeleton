<?php
namespace Bee\Db;

/**
 * 数据库连接层
 *
 * @package Bee\Db
 */
abstract class Layer implements LayerInterface
{
    /**
     * @var PoolInterface
     */
    protected $masterPool;

    /**
     * @var PoolInterface
     */
    protected $slavePool;

    /**
     * MySQL constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        // 如果存在主节点配置，初始化
        if (isset($config['master'])) {
            $this->initMasterPool($config['master']);
        }

        // 如果存在从节点配置，初始化
        if (isset($config['slave'])) {
            $this->initSlavePool($config['slave']);
        }

        // 如果只存在一个配置，主从各创建
        if (isset($config['host'])) {
            $this->initMasterPool($config);
            $this->initSlavePool($config);
        }
    }

    /**
     * 初始化主节点连接池
     *
     * @param array $config
     * @return mixed
     */
    abstract protected function initMasterPool(array $config);

    /**
     * 初始化从节点连接池
     *
     * @param array $config
     * @return mixed
     */
    abstract protected function initSlavePool(array $config);
}