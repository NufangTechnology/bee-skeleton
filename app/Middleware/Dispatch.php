<?php
namespace Star\Middleware;

use Bee\Exception;
use Bee\Http\Application;
use Bee\Http\Middleware;
use Bee\Http\Context;
use Star\Util\ThrowException;
use Star\Util\ThrowExceptionHandler;

/**
 * 路由请求派发中间件
 *
 * @package Star\Middleware
 */
class Dispatch extends Middleware
{
    /**
     * 中间件业务执行体
     *
     * @param Application $application
     * @param Context $context
     * @param mixed $parameters
     * @return mixed
     * @throws \Bee\Router\Exception
     */
    public function call(Application $application, Context $context, $parameters = null)
    {
        $routeHandler = $application->getRouteHandler();

        // 执行业务
        $returnValue  = $routeHandler->callMethod($application, $parameters);
        // 检查是否需要 json 序列化
        if ($context->isOutputJson()) {
            $returnValue = json_encode($returnValue);
        }
        // 将返回值注入上下文
        $context->setContent($returnValue);

        return true;
    }
}
