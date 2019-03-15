<?php
namespace Star\Util;

use Bee\Db\MySQL;
use Bee\Di\Container as Di;

/**
 * 模型基类
 *
 * @package Star\Util
 */
abstract class Model
{
    /**
     * @var string
     */
    protected $dbName = 'service.mysql';

    /**
     * @var MySQL
     */
    protected $mysql;

    /**
     * @var string
     */
    protected $table;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->mysql = Di::getDefault()->getShared($this->dbName);

        // 执行模型内部初始化
        $this->initialize();
    }

    /**
     * 模型初始化
     */
    public function initialize(){}

    /**
     * 根据id获取数据记录
     *
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function findFirstById($id)
    {
        $sql   = "SELECT * FROM {$this->table} WHERE id = ?";
        $binds = [$id];

        return $this->findFirst($sql, $binds);
    }

    /**
     * 执行 SQL 并获取一条结果
     *
     * @param string $sql
     * @param array $binds
     * @return array
     * @throws \Exception
     */
    public function findFirst(string $sql, array $binds = [])
    {
        // 优先使用从库查询，如果从库没有记录尝试从主库查询
        $result = $this->mysql->slave($sql, $binds);

        if (empty($result)) {
            $result = $this->mysql->master($sql, $binds);
        }

        if (empty($result)) {
            return [];
        }

        return $result[0];
    }

    /**
     * [主库]执行 SQL 并获取一条结果
     *
     * @param string $sql
     * @param array $binds
     * @return array
     * @throws \Exception
     */
    public function findFirstByMaster(string $sql, array $binds = [])
    {
        $result = $this->mysql->master($sql, $binds);

        if (empty($result)) {
            return [];
        }

        return $result[0];
    }
}