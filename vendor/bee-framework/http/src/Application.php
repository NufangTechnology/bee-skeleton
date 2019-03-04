<?php
namespace Bee\Http;

use Swoole\Http\Request;
use Swoole\Http\Response;
use Bee\Router\Handler as RouteHandler;

/**
 * Application
 *
 * @package Star\Util
 */
class Application
{
    /**
     * @var array
     */
    protected $meddlers = [];

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var RouteHandler
     */
    protected $routeHandler;

    /**
     * Application constructor.
     *
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        // 上下文初始化
        $this->context = new Context($request, $response);
    }

    /**
     * 注册中间件
     *
     * @param Middleware $middleware
     * @return Application
     */
    public function use(Middleware $middleware)
    {
        $this->meddlers[] = $middleware;

        return $this;
    }

    /**
     * 批量注册中间件
     *
     * @param array $config
     * @return Application
     */
    public function map(array $config)
    {
        foreach ($config as $item) {
            $this->use(new $item);
        }

        return $this;
    }

    /**
     * 执行请求业务处理
     *  - 设置请求超时定时器
     *  - 执行中间件业务
     *  - 清除超时定时器
     *  - 业务处理结束，获取返回值
     */
    public function handle()
    {
        $result = null;

        foreach ($this->meddlers as $middleware) {
            // 执行中间件业务
            $result = call_user_func($middleware, $this, $result);

            // 中间件返回 false ，请求停止向下传递
            if ($result === false) {
                break;
            }
        }

        // 返回响应数据
        $this->response();
    }

    /**
     * 返回响应数据
     */
    public function response()
    {
        // 待发送至客户端数据
        $content  = $this->context->getContent();

        // 处理返回内容
        $response = $this->context->getResponse();
        // 设置待发送给客户端数据
        $response->setContent($content);
        // 向客户端发送内容并结束请求
        $response->send();
    }

    /**
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }

    /**
     * @param RouteHandler $routeHandler
     */
    public function setRouteHandler(RouteHandler $routeHandler)
    {
        $this->routeHandler = $routeHandler;
    }

    /**
     * @return RouteHandler
     */
    public function getRouteHandler(): RouteHandler
    {
        return $this->routeHandler;
    }
}
