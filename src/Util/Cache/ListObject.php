<?php

namespace Star\Util\Cache;

/**
 * List 缓存结构
 *  - 基于字符串[List]
 *
 * @package Star\Util\Cache
 */
Trait ListObject
{
    /**
     * rpush with bulk support
     * https://redis.io/commands/rpush
     *
     * @param string $key
     * @param string|array $value
     * @return mixed
     */
    public function rpush($key, $value)
    {
        if (is_array($value)) {
            return self::connect()->rpush(self::$prefix . $key, ...$value);
        }

        return self::connect()->rpush(self::$prefix . $key, $value);
    }

    /**
     * lpush
     * https://redis.io/commands/lpush
     *
     * @param string $key
     * @param string $value
     * @return mixed
     */
    public function lpush($key, $value)
    {
        return self::connect()->lpush(self::$prefix . $key, $value);
    }

    /**
     * @see lLen()
     * https://redis.io/commands/llen
     *
     * @param string $key
     * @return mixed
     */
    public function lsize($key)
    {
        return self::connect()->lsize(self::$prefix . $key);
    }

    /**
     * blpop a list
     * https://redis.io/commands/blpop
     *
     * @param array $keys
     * @param int $timeout
     * @return mixed
     */
    public function blpop($keys, $timeout)
    {
        return self::connect()->blpop(array_map(function ($each) {
            return self::$prefix . $each;
        }, $keys), $timeout);
    }
}
