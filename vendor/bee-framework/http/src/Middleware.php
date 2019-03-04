<?php
namespace Bee\Http;

/**
 * Middleware
 *
 * @package Star\Util
 */
abstract class Middleware
{
    /**
     * @param Application $application
     * @param null $parameters
     * @return mixed
     */
    public function __invoke(Application $application, $parameters = null)
    {
        return $this->call($application, $application->getContext(), $parameters);
    }

    /**
     * 中间件业务执行体
     *
     * @param Application $application
     * @param Context $context
     * @param mixed $parameters
     * @return mixed
     */
    abstract public function call(Application $application, Context $context, $parameters = null);
}
