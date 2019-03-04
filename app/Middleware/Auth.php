<?php
namespace Star\Middleware;

use Bee\Http\Context;
use Bee\Http\Application;
use Bee\Http\Middleware;

/**
 * 身份鉴权中间件
 *
 * @package Star\Middleware
 */
class Auth extends Middleware
{
    /**
     * 中间件业务执行体
     *
     * @param Application $application
     * @param Context $context
     * @param mixed $parameters
     * @return mixed
     * @throws \Exception
     */
    public function call(Application $application, Context $context, $parameters = null)
    {
    }
}
