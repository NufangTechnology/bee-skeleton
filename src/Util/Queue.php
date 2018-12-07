<?php
namespace Star\Util;

use Bee\Injectable;
use Phalcon\Config;

/**
 * 消息队列封装
 *
 * @package Star\Util
 *
 * @property Config $global
 */
class Queue extends Injectable
{
    /**
     * Group交换器名称
     */
    const EX_GROUP = 'EX_DEMO';

    /**
     * 加入星球路由名称
     */
    const ROUTE_JOIN_GROUP = 'example/route';

    /**
     * 发送加入星球消息
     *
     * @param $data
     * @param string $name
     */
    public function example($data, $name = '')
    {
        // 服务魔人名称
        $service   = 'service.mq.producer';
        // 指定名称的服务
        if ($name) {
            $service .= ".{$name}";
        }

        // 投递消息
        $this->di->getShared($service)->publish(
            self::EX_GROUP,
            self::ROUTE_JOIN_GROUP,
            [
                'no'      => $this->global['requestId'],
                'user_id' => $this->global['userId'],
                'data'    => $data
            ]
        );
    }
}
