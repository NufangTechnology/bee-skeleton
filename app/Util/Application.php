<?php
namespace Star\Util;

use Bee\Exception;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Bee\RuntimeException;
use Bee\Http\Context;
use Bee\Router\Handler as RouteHandler;
use Bee\Di\Container as Di;

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
     * 服务 reduce
     */
    protected function reduce()
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
        try {
            $this->reduce();
        } catch (\Throwable $e) {

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
        $content = $this->context->getContent();


        // 处理返回内容
        $response = $this->context->getResponse();
        // 设置待发送给客户端数据
        $response->setContent($content);
        // 向客户端发送内容并结束请求
        $response->send();
    }

    /**
     * @param \Throwable $e
     */
    public function collectionException(\Throwable $e)
    {
        // 拼接返回至客户端错误信息
        if ($e instanceof RuntimeException) {
            $content['result'] = false;
            $content['code']   = $e->getCode();
            $content['msg']    = $e->getMessage();
            $content['info']   = $e->getData();
        } else {
            $content['result'] = false;
            $content['code']   = $e->getCode();
            $content['msg']    = '服务异常，请稍后重试!';
        }
        // 保存至待返回客户端内容
        $this->context->setContent($content);

        $trace = $e->getTrace();
        // 拼接 trace 信息
        $log   = [
            'name'     => 'app-management',
            'message'  => $e->getMessage(),
            'code'     => $e->getCode(),
            'line'     => $trace[0]['line'] ?? 0,
            'function' => $trace[0]['function'] ?? '',
            'class'    => get_class($e),
            'file'     => $trace[0]['file'] ?? '',
            'args'     => $trace[0]['args'] ?? [],
        ];
        // 异常 trace 信息保存至上下文日志中
        $this->context->setLog($log);

        // 记录上下文快照至日志
        $logger = Di::getDefault()->getShared('service.logger');
        $logger->error($this->context);
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
