<?php
namespace Star\Middleware;

use Bee\Exception;
use Star\Util\Middleware;
use Star\Util\Application;
use Bee\Http\Context;

/**
 * 路由请求派发中间件
 *
 * @package Star\Middleware
 */
class Dispatch extends Middleware
{
    /**
     * 中间件业务执行体
     *
     * @param Application $application
     * @param Context $context
     * @param mixed $parameters
     * @return mixed
     */
    public function call(Application $application, Context $context, $parameters = null)
    {
        $continue     = true;
        $routeHandler = $application->getRouteHandler();

        try {
            // 执行业务
            $returnValue = $routeHandler->callMethod($application, $parameters);

            // 返回数据为数组，拼接成约定格式
            if (is_array($returnValue)) {
                $returnValue = [
                    'result' => true,
                    'info'   => $returnValue,
                ];
            }
        } catch (Exception $e) {
            // 拼接一场时返回的数据
            $returnValue = [
                'result' => false,
                'code'   => $e->getCode(),
                'msg'    => $e->getMessage(),
            ];

            // 收集运行异常信息
            $context->setLog($e->toArray());
            // 终止请求往下传递
            $continue = false;
        }

        // 检查是否需要 json 序列化
        if ($context->isOutputJson()) {
            $returnValue = json_encode($returnValue);
        }

        // 将返回值注入上下文
        $context->setContent($returnValue);

        return $continue;
    }
}
