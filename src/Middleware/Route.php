<?php
namespace Star\Middleware;

use Star\Util\Exception;
use Star\Util\Status;
use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;
use Star\Util\Exception\UrlNotFoundException;

/**
 * 路由中间件
 *
 * @package Star\Middleware
 */
class Route
{
    /**
     * 请求URL不存在事件
     *
     * @param Event $event
     * @param Micro $micro
     * @throws UrlNotFoundException
     */
    public function beforeNotFound(Event $event, Micro $micro)
    {
        Exception::urlNotFound(Status::E_404000);
    }

    /**
     * 业务执行前对参数进行解密
     *
     * @param Event $event
     * @param Micro $micro
     * @return bool
     */
    public function beforeExecuteRoute(Event $event, Micro $micro)
    {
        // 重置路由匹配的类
        $activeHandler = $micro->getActiveHandler();
        if (is_array($activeHandler)) {
            // 重新实例化业务类
            $definition       = $activeHandler[0]->getDefinition();
            unset($activeHandler[0]);
            $activeHandler[0] = new Micro\LazyLoader($definition);
            // 更新路由handle
            $micro->setActiveHandler($activeHandler);
        }

        return true;
    }
}
