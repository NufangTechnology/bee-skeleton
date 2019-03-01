<?php
namespace Bee\Router;

/**
 * Interface CollectionInterface
 *
 * @package Star\Router
 */
interface CollectionInterface
{
    /**
     * 设置Handler class
     *
     * @param string $class
     * @return $this
     */
    public function setHandler(string $class);

    /**
     * 获取handler class
     *
     * @return string
     */
    public function getHandler();

    /**
     * 设置URL前缀
     *
     * @param string $prefix
     * @return $this
     */
    public function setPrefix(string $prefix);

    /**
     * 获取URL前缀
     *
     * @return string
     */
    public function getPrefix();

    /**
     * Cet Method
     *
     * @param string $routePattern
     * @param string $handler
     * @return $this
     */
    public function get(string $routePattern, string $handler);

    /**
     * Post Method
     *
     * @param string $routePattern
     * @param string $handler
     * @return $this
     */
    public function post(string $routePattern, string $handler);

    /**
     * Put Method
     *
     * @param string $routePattern
     * @param string $handler
     * @return $this
     */
    public function put(string $routePattern, string $handler);

    /**
     * Delete Method
     *
     * @param string $routePattern
     * @param string $handler
     * @return $this
     */
    public function delete(string $routePattern, string $handler);

    /**
     * Patch Method
     *
     * @param string $routePattern
     * @param string $handler
     * @return $this
     */
    public function patch(string $routePattern, string $handler);

    /**
     * Options Method
     *
     * @param string $routePattern
     * @param string $handler
     * @return $this
     */
    public function options(string $routePattern, string $handler);

    /**
     * 获取所有路由规则项
     *
     * @return mixed
     */
    public function getHandlers() : array;
}
