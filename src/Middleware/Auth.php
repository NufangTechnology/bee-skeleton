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
        return true;
    }
}
