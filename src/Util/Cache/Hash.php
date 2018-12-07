<?php

namespace Star\Util\Cache;

/**
 * Hash缓存结构
 *  - 基于哈希表[hash]
 *
 * @package Star\Util\Cache
 */
trait Hash
{
    /**
     * 保存
     *
     * @param string $key
     * @param string $field 主记录ID
     * @param string $value
     */
    public function save($key, $field, $value)
    {
        self::connect()->hSet(self::$prefix . $key, $field, $value);
    }

    /**
     * $redis->hMset($baoinfo_pre . $add_data['bao_sn'], [
     * 'begin_time' => $begin_time,
     * 'player_num' => 0,
     * 'pre_img'    => $pre_img
     * ]);
     *
     * @param string $key
     * @param array $data
     */
    public function bulkSave($key, $data)
    {
        self::connect()->hMset(self::$prefix . $key, $data);
    }

    /**
     * $redis->hMget($this->bao_Rkey, ['status', 'share_num'])
     *
     * @param string $key
     * @param array $field
     * @return mixed
     */
    public function bulkGet($key, $field)
    {
        return self::connect()->hMget(self::$prefix . $key, $field);
    }

    /**
     * 获取
     *
     * @param string $key
     * @param string $field
     * @return string|null
     */
    public function get($key, $field)
    {
        return self::connect()->hGet(self::$prefix . $key, $field);
    }

    /**
     * 删除
     *
     * @param string $key
     * @param string $field 记录ID
     */
    public function delete($key, $field)
    {
        self::connect()->hDel(self::$prefix . $key, $field);
    }

    /**
     * 计数器做自增
     *
     * @param string $key
     * @param string $field
     * @param int $number
     */
    public function inc($key, $field, $number = 1)
    {
        self::connect()->hIncrBy(self::$prefix . $key, $field, $number);
    }

    /**
     * 计数器自减
     *
     * @param string $key
     * @param string $field
     * @param int $number
     */
    public function dec($key, $field, $number = -1)
    {
        self::connect()->hIncrBy(self::$prefix . $key, $field, $number);
    }
}