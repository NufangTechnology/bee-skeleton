<?php
namespace Star\Util;

use Phalcon\Di;

/**
 * Redis数据操作基类
 *
 * @package Star\Core
 *
 * @property string $prefix
 */
abstract class Redis
{
    /**
     * redis连接名称
     *
     * @var string
     */
    static protected $name = 'default';

    /**
     * @var \Redis[]
     */
    static protected $redis = [];

    /**
     * 数据所在库
     *
     * @var int
     */
    static protected $index = 0;

    /**
     * @var string
     */
    static protected $prefix = '';

    /**
     * Redis constructor.
     *
     * @param string $name
     */
    public function __construct($name = '')
    {
        if (!empty($name)) {
            self::$name = $name;
        }

        if (method_exists($this, '_init')) {
            $this->_init();
        }
    }

    /**
     * 获取redis连接
     *  - 根据name参数获取不同连接实例
     *
     * @param string $name
     * @param int $index
     * @return \Redis
     */
    static public function connect($name = '', $index = 0)
    {
        if (empty($name)) {
            $name = static::$name;
        }

        if ($index == 0) {
            $index = static::$index;
        }
        
        if (!isset(self::$redis[$name])) {
            self::$redis[$name] = Di::getDefault()->getShared("db.redis.{$name}");
        }

        //检测连接
        try{
            self::$redis[$name]->ping();
        } catch (\Throwable $throwable) {
            self::$redis[$name] = Di::getDefault()->getShared("db.redis.{$name}");
        }

        // 指定缓存所在库
        self::$redis[$name]->select($index);

        return self::$redis[$name];
    }

    /**
     * 获取缓存前缀
     *
     * @return string
     */
    static public function getPrefix()
    {
        return static::$prefix;
    }
}