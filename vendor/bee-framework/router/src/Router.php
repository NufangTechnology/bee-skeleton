<?php
namespace Bee\Router;

/**
 * Class1 Router
 *
 * @package Star\Router
 */
class Router implements RouterInterface
{
    /**
     * @var array
     */
    protected $handlers = [];

    /**
     * 执行路由匹配
     *
     * @param string $method
     * @param string $urlPath
     * @return Handler|bool
     */
    public function match(string $method, string $urlPath)
    {
        return $this->handlers[$method][$urlPath] ?? false;
    }

    /**
     * 路由挂载
     *
     * @param CollectionInterface[]
     */
    public function map(array $collections)
    {
        foreach ($collections as $collection) {
            $this->mount($collection);
        }
    }

    /**
     * @param CollectionInterface $collection
     */
    public function mount(CollectionInterface $collection)
    {
        $handlers = $collection->getHandlers();

        foreach ($handlers as $handler) {
            $this->setHandler($handler);
        }
    }

    /**
     * @param Handler $handler
     */
    public function setHandler(Handler $handler): void
    {
        $method       = $handler->getMethod();
        $routePattern = $handler->getRoutePattern();

        $this->handlers[$method][$routePattern] = $handler;
    }
}