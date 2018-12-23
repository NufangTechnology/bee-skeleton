<?php
namespace Star\Middleware;

use Star\Util\Micro;
use Phalcon\Events\Event;

/**
 * 用户身份鉴权中间件
 *
 * @package Star\Middleware
 */
class Auth
{
    /**
     * 路由匹配前置事件
     *  - 身份认证
     *  - 生成Request ID
     *
     * @param Event $event
     * @param Micro $micro
     * @return bool
     */
    public function beforeExecuteRoute(Event $event, Micro $micro)
    {
        // 全局共享信息对象
        $global = $micro->getSharedService('global');

        // HTTP 请求唯一编号
        // 由自增数字加业务处理节点编号等信息构成
        // 具体结构不限
        $global['uniqueId'] = $this->createUniqueId();

        // 用户ID，保存至每条日志中，便于对特定用户进行日志追踪
        // 等于0表示游客或者身份解析失败
        // 等于1表示系统日志
        $global['userId']   = 0;

        return true;
    }

    /**
     * 为每次HTTP请求请求生成唯一ID
     *  - FIXME: 推荐改由网关生成 ID 前缀，进入每个应用实例拼接节点编号即可
     *
     * @return string
     */
    private function createUniqueId()
    {
        return str_replace(' ', '-', 'HTTP' . str_replace('0.', ' ', microtime()));
    }
}
