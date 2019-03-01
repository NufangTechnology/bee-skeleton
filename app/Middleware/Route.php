<?php
namespace Star\Middleware;

use Bee\Di\Container;
use Bee\Http\Context;
use Bee\Router\Handler as RouteHandler;
use Star\Util\Middleware;
use Star\Util\Application;
use Star\Util\ThrowException;

/**
 * 路由中间件
 *
 * @package Star\Middleware
 */
class Route extends Middleware
{
    /**
     * 中间件业务执行体
     *
     * @param Application $application
     * @param Context $context
     * @param mixed $parameters
     * @return mixed
     * @throws ThrowException\UrlNotFoundException
     */
    public function call(Application $application, Context $context, $parameters = null)
    {
        $request = $context->getRequest();
        // 请求方法
        $method   = $request->getMethod();

        // HEAD - 腾讯负载心跳处理
        if ($method == 'HEAD') {
            $context->setOutputJson(false);
            return false;
        }

        $router  = Container::getDefault()->getShared('router');
        // 获取 URL PATH
        $urlPath = $request->getURI();

        // 执行路由匹配
        $handler = $router->match($method, $urlPath);
        if ($handler instanceof RouteHandler) {
            $application->setRouteHandler($handler);
        } else {
            ThrowException::urlNotFound();
        }

        return true;
    }
}
