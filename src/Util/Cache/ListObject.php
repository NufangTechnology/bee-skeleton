<?php

namespace Star\Util\Cache;

/**
 * List 缓存结构
 *  - 基于列表[List]
 *
 * @package Star\Util\Cache
 */
Trait ListObject
{
    /**
     * Pops a value from the tail of a list, and pushes it to the front of another list.
     * Also return this value.
     *
     * @since   redis >= 1.1
     * @param   string $srcKey
     * @param   string $dstKey
     * @return  string  The element that was moved in case of success, FALSE in case of failure.
     * @link    https://redis.io/commands/rpoplpush
     */
    public function rpoplpush($srcKey, $dstKey)
    {
        return self::connect()->rpoplpush(self::$prefix . $srcKey, self::$prefix . $dstKey);
    }

    /**
     * Removes the first count occurences of the value element from the list.
     * If count is zero, all the matching elements are removed. If count is negative,
     * elements are removed from tail to head.
     *
     * @param   string $key
     * @param   string $value
     * @param   int $count
     * @return  int     the number of elements to remove
     * bool FALSE if the value identified by key is not a list.
     * @link    https://redis.io/commands/lrem
     */
    public function lRem($key, $value, $count)
    {
        return self::connect()->lRem(self::$prefix . $key, $value, $count);
    }

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
     * rpop
     * https://redis.io/commands/rpop
     *
     * @param string $key
     * @return mixed
     */
    public function rpop($key)
    {
        return self::connect()->rpop(self::$prefix . $key);
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
