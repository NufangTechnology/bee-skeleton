<?php
namespace Star\Util\Cache;

/**
 * 排序缓存结构
 *  - 基于有序集合[sorted set]
 *
 * @package Star\Util\Cache
 */
trait Sort
{
    /**
     * 保存数据值集合
     *
     * @param string|int $cate
     * @param int $member
     * @param int $score
     * @return Sort
     */
    public function save($cate, $member, $score)
    {
        $table = self::$prefix . $cate;
        self::connect()->zAdd($table, $score, $member);

        return $this;
    }

    /**
     * 通过成员rank获取列表区间
     *
     * @param string|int $cate
     * @param string $member
     * @param int $limit
     * @param bool $asc
     * @return array
     */
    public function findByMember($cate, $member = '', $limit = 5, $asc = true)
    {
        $table = self::$prefix . $cate;

        // 未传起始值，视为获取第一页
        if (empty($member)) {
            $offset = 0;
        } else {
            // 获取member排名得到分页的起始位置
            $offset = self::connect()->ZrevRank($table, $member);
            if ($offset === false) {
                return [];
            }

            // 传进来的offsetId为上一个列表最后一条记录ID，写一个列表起始位置加1
            $offset++;
        }

        // 取值量 = 起始位置 + 要取值数量
        $limit = $offset + $limit - 1;

        // 获取数据列表
        if ($asc) {
            $list = self::connect()->zRevRange($table, $offset, $limit);
        } else {
            $list = self::connect()->zRange($table, $offset, $limit);
        }
        if ($list == false) {
            return [];
        }

        return $list;
    }

    /**
     * 通过起始值获取列表区间
     *
     * @param int $cate
     * @param int $offset
     * @param int $limit
     * @param bool $asc
     * @return array
     */
    public function findByOffset($cate, $offset = 0, $limit = 1, $asc = true)
    {
        $table = self::$prefix . $cate;

        // 取值量 = 起始位置 + 要取值数量
        $limit = $offset + $limit - 1;

        // 获取数据列表
        if ($asc) {
            $list = self::connect()->zRevRange($table, $offset, $limit);
        } else {
            $list = self::connect()->zRange($table, $offset, $limit);
        }
        if ($list == false) {
            return [];
        }

        return $list;
    }

    /**
     * 获取索引最后一条信息
     *  - rank为0的那条信息
     *
     * @param $cate
     * @param bool $asc
     * @return mixed
     */
    public function last($cate, $asc = true)
    {
        $last = $this->findByOffset($cate, 0, 1, $asc);
        return array_shift($last);
    }

    /**
     * 获取总记录数
     *
     * @param string|int $cate
     * @return int
     */
    public function count($cate)
    {
        $table = self::$prefix . $cate;
        return self::connect()->zCard($table);
    }

    /**
     * 从索引中删除制定member成员
     *
     * @param mixed $cate
     * @param mixed $member
     */
    public function delete($cate, $member)
    {
        $table = self::$prefix . $cate;
        self::connect()->zRem($table, $member);
    }

    /**
     * 获取置顶成员score
     *
     * @param $cate
     * @param $member
     * @return string|false
     */
    public function get($cate, $member)
    {
        $table = self::$prefix . $cate;
        return self::connect()->zScore($table, $member);
    }

    /**
     * 检查成员是否存在
     *
     * @param $cate
     * @param $member
     * @return bool
     */
    public function exist($cate, $member)
    {
        return boolval($this->get($cate, $member));
    }

    /**
     * 删除指定key索引
     *
     * @param $cate
     */
    public function del($cate)
    {
        $table = self::$prefix . $cate;
        self::connect()->del($table);
    }

    /**
     * 合并成指定的key
     *
     * @param $outString
     * @param $inArrays
     */
    public function union($outString, $inArrays)
    {
        $table = self::$prefix . $outString;
        $array = [];
        foreach($inArrays as $in)
        {
            $array[] = self::$prefix . $in;
        }
        self::connect()->zUnion($table,$array);
    }
}
