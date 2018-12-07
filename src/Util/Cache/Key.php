<?php
namespace Star\Util\Cache;

/**
 * key缓存结构
 *  - 基于字符串[string]
 *
 * @package Star\Util\Cache
 */
Trait Key
{
    /**
     * 保存一条数据
     *
     * @param $cate
     * @param $value
     * @param bool $expire
     */
    public function save($cate, $value, $expire = false)
    {
        $key = self::$prefix . $cate;

        if ($expire) {
            self::connect()->setex($key, $expire, $value);
        } else {
            self::connect()->set($key, $value);
        }
    }

    /**
     * 获取指定数据
     *
     * @param $cate
     * @return mixed
     */
    public function find($cate)
    {
        $key = self::$prefix . $cate;
        return self::connect()->get($key);
    }

    /**
     * 检查指定数据是否存在
     *
     * @param string $cate
     * @return bool
     */
    public function exist($cate)
    {
        $key = self::$prefix . $cate;
        return self::connect()->exists($key);
    }

    /**
     * 删除指定数据
     *
     * @param string $cate
     */
    public function delete($cate)
    {
        $key = self::$prefix . $cate;
        self::connect()->del($key);
    }

    /**
     * 设置指定数据过期时间
     *
     * @param string $cate
     * @param int $expire 过期时间（秒）
     */
    public function expire($cate, $expire)
    {
        $key = self::$prefix . $cate;
        self::connect()->expire($key, $expire);
    }

    /**
     * 指定数据加1
     *
     * @param string $cate
     */
    public function inc($cate)
    {
        $key = self::$prefix . $cate;
        self::connect()->incr($key);
    }

    /**
     * 指定数据减1
     *
     * @param string $cate
     */
    public function dec($cate)
    {
        $key = self::$prefix . $cate;
        self::connect()->decr($key);
    }

    /**
     * 指定数据加上指定值
     *
     * @param string $cate
     * @param int $number
     */
    public function incBy($cate, $number)
    {
        $key = self::$prefix . $cate;
        self::connect()->incrBy($key, $number);
    }

    /**
     * 指定数据减去指定值
     *
     * @param string $cate
     * @param int $number
     */
    public function decBy($cate, $number)
    {
        $key = self::$prefix . $cate;
        self::connect()->decrBy($key, $number);
    }
}
