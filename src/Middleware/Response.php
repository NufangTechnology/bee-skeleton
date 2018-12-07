<?php
namespace Star\Middleware;

use Star\Util\Exception;
use Phalcon\Events\Event;
use Star\Util\Micro;

/**
 * 响应处理中间件
 *  - 处理成功请求内容响应
 *
 * @package Star\Middleware
 */
class Response
{
    /**
     * 处理业务返回内容
     *
     * @param Event $event
     * @param Micro $micro
     * @param array $returnValue
     *
     * @throws \Exception
     */
    public function afterHandleRequest(Event $event, Micro $micro, $returnValue = [])
    {
        if (!empty($returnValue)) {
            // 计算业务处理总花费时间
            $returnValue['time'] = $micro->expendTime();
            $micro->response->setContent(json_encode($returnValue));
        }

        $micro->response->setContentType('application/json', 'utf-8');
        $micro->response->send();
    }

    /**
     * 异常响应内容
     *
     * @param Event $event
     * @param Micro $micro
     * @param \Throwable $e
     * @throws \Exception
     */
    public function afterHandleException(Event $event, Micro $micro, \Throwable $e)
    {
        // 准备返回数据
        $data = [
            'result' => false,
            'error'   => $e->getCode(),
            'msg'    => $e->getMessage(),
        ];

        if ($e instanceof Exception) {
            $data['info'] = $e->data;
        } else {
            $data['info'] = new \stdClass;
        }

        $this->afterHandleRequest($event, $micro, $data);
    }

    /**
     * 错误响应内容
     *
     * @param Event $event
     * @param Micro $micro
     * @param array $params
     *
     * @throws \Exception
     */
    public function afterHandleError(Event $event, Micro $micro, $params = [])
    {
        $data = [
            'result' => false,
            'error'   => 500,
            'msg'    => '系统错误，请稍后重试',
        ];

        $this->afterHandleRequest($event, $micro, $data);
    }

    /**
     * Shutdown响应内容
     *
     * @param Event $event
     * @param Micro $micro
     *
     * @throws \Exception
     */
    public function afterHandleShutdown(Event $event, Micro $micro)
    {
        // 获取错误信息
        $error = error_get_last();

        if ($error) {
            // 准备返回数据
            $data = [
                'result' => false,
                'error'   => 500,
                'msg'    => '系统异常，请稍后重试',
            ];

            $this->afterHandleRequest($event, $micro, $data);
        }
    }
}