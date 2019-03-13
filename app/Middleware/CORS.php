<?php
namespace Star\Middleware;

use Bee\Http\Context;
use Bee\Http\Application;
use Bee\Http\Middleware;

/**
 * 跨域处理中间件
 *
 * @package Star\Middleware
 */
class CORS extends Middleware
{
    /**
     * 中间件业务执行体
     *
     * @param Application $application
     * @param Context $context
     * @param mixed $parameters
     * @return mixed
     */
    public function call(Application $application, Context $context, $parameters = null)
    {
        $response = $context->getResponse();
        $request  = $context->getRequest();
        // 请求方法
        $method   = $request->getMethod();

        // HEAD - 腾讯负载心跳处理
        if ($method == 'HEAD') {
            $context->setOutputJson(false);
            $context->setContent('');
            return false;
        }

        // 跨域头信息
        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Credentials', 'true');
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS, HEAD');
        $response->setHeader('Access-Control-Allow-Headers', "origin, x-requested-with, content-type, token");
        $response->setHeader('Access-Control-Max-Age', '86400');

        if ($request->getMethod() == 'OPTIONS') {
            $context->setOutputJson(false);
            $context->setContent('');
            return false;
        }

        return true;
    }
}