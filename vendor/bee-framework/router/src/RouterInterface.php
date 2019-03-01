<?php
namespace Bee\Router;

interface RouterInterface
{
    /**
     * 执行路由匹配
     *
     * @param string $method
     * @param string $urlPath
     * @return mixed
     */
    public function match(string $method, string $urlPath);

    /**
     * Mount routes
     *
     * @param CollectionInterface[]
     */
    public function map(array $collection);

    /**
     * @param CollectionInterface $collection
     */
    public function mount(CollectionInterface $collection);
}
