<?php
namespace Bee\Router;

/**
 * 路由规则收集器
 *
 * @package Funny\Router
 */
class Collection implements CollectionInterface
{
    /**
     * @var array
     */
    protected $handlers = [];

    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * @var string
     */
    protected $class = '';

    /**
     * CollectionInterface constructor.
     *
     * @param string $class
     * @param string $prefix
     */
    public function __construct(string $class = '', string $prefix = '')
    {
        if ($class) {
            $this->setClass($class);
        }

        if ($prefix) {
            $this->setPrefix($prefix);
        }
    }

    /**
     * 解析并完成URL组装
     *
     * @param string $method
     * @param string $routePattern
     * @param string $handler
     */
    protected function addMap(string $method, string $routePattern, string $handler)
    {
        $routePattern = trim($routePattern, '/');

        // 拼接路由规则前缀
        if ($this->prefix) {
            if ($routePattern) {
                $routePattern = $this->prefix . '/' . $routePattern;
            } else {
                $routePattern = $this->prefix;
            }
        } else {
            $routePattern = '/' . $routePattern;
        }

        // 创建路由实例
        $this->handlers[] = new Handler($method, $routePattern, [$this->class, $handler]);
    }

    /**
     * 获取所有路由规则项
     *
     * @return mixed
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * 设置Handler class
     *
     * @param string $class
     * @return $this
     */
    public function setHandler(string $class)
    {
        $this->class = '\\' . trim($class, '\\');

        return $this;
    }

    /**
     * 获取handler class
     *
     * @return string
     */
    public function getHandler()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass(string $class): void
    {
        $this->class = '\\' . trim($class, '\\');
    }

    /**
     * 设置URL前缀
     *
     * @param string $prefix
     * @return $this
     */
    public function setPrefix(string $prefix)
    {
        $this->prefix = trim($prefix, '/');

        // 前缀不为根路径
        if ($this->prefix) {
            $this->prefix = '/' . $this->prefix;
        }

        return $this;
    }

    /**
     * 获取URL前缀
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Cet Method
     *
     * @param string $routePattern
     * @param string $handler
     * @return $this
     */
    public function get(string $routePattern, string $handler)
    {
        // 创建一条URL规则
        $this->addMap('GET', $routePattern, $handler);

        return $this;
    }

    /**
     * Post Method
     *
     * @param string $routePattern
     * @param string $handler
     * @return $this
     */
    public function post(string $routePattern, string $handler)
    {
        // 创建一条URL规则
        $this->addMap('POST', $routePattern, $handler);

        return $this;
    }

    /**
     * Put Method
     *
     * @param string $routePattern
     * @param string $handler
     * @return $this
     */
    public function put(string $routePattern, string $handler)
    {
        // 创建一条URL规则
        $this->addMap('PUT', $routePattern, $handler);

        return $this;
    }

    /**
     * Delete Method
     *
     * @param string $routePattern
     * @param string $handler
     * @return $this
     */
    public function delete(string $routePattern, string $handler)
    {
        // 创建一条URL规则
        $this->addMap('DELETE', $routePattern, $handler);

        return $this;
    }

    /**
     * Patch Method
     *
     * @param string $routePattern
     * @param string $handler
     * @return $this
     */
    public function patch(string $routePattern, string $handler)
    {
        // 创建一条URL规则
        $this->addMap('PATCH', $routePattern, $handler);

        return $this;
    }

    /**
     * Options Method
     *
     * @param string $routePattern
     * @param string $handler
     * @return $this
     */
    public function options(string $routePattern, string $handler)
    {
        // 创建一条URL规则
        $this->addMap('OPTIONS', $routePattern, $handler);

        return $this;
    }
}
