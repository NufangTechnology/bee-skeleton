<?php
namespace Bee\Router;

/**
 * Handler
 *
 * @package Star\Router
 */
class Handler implements HandlerInterface
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $handler;

    /**
     * @var string
     */
    protected $routePattern;

    /**
     * Handler
     *
     * @param string $method
     * @param string $routePattern
     * @param array $handler
     */
    public function __construct(string $method, string $routePattern, array $handler)
    {
        $this->method       = $method;
        $this->routePattern = $routePattern;
        $this->handler      = $handler;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getHandler(): array
    {
        return $this->handler;
    }

    /**
     * @return string
     */
    public function getRoutePattern(): string
    {
        return $this->routePattern;
    }

    /**
     * 执行路由项业务
     *
     * @param $application
     * @param mixed $parameters
     * @return mixed
     */
    public function callMethod($application, $parameters = null)
    {
        $class = new $this->handler[0];
        $class->setApp($application);

        return call_user_func([$class, $this->handler[1]], $parameters);
    }
}
