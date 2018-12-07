<?php
namespace Star\Middleware;

use Phalcon\Events\Event;
use Star\Util\Micro;

/**
 * 跨域处理中间件
 *
 * @package Star\Middleware
 */
class CORS
{
    /**
     * 处理跨域header信息
     *
     * @param Event $event
     * @param Micro $micro
     * @return bool
     */
    public function beforeHandleRoute(Event $event, Micro $micro)
    {
        // 跨域头信息
        $micro->response->setHeader('Access-Control-Allow-Origin', '*');
        $micro->response->setHeader('Access-Control-Allow-Credentials', 'true');
        $micro->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS, HEAD');
        $micro->response->setHeader('Access-Control-Allow-Headers', "origin, x-requested-with, content-type, token");
        $micro->response->setHeader('Access-Control-Max-Age', '86400');

        if ($micro->request->getMethod() == 'OPTIONS') {
            $event->stop();

            return false;
        }

        return true;
    }
}