<?php
namespace Bee\Router;

use Bee\Http\Context;

/**
 * Interface HandlerInterface
 *
 * @package Star\Router
 */
interface HandlerInterface
{
    /**
     * 执行路由项业务
     *
     * @param Context $context
     * @param mixed $parameters
     * @return mixed
     */
    public function callMethod(Context $context, $parameters = null);
}
