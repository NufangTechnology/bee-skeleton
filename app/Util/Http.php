<?php
namespace Star\Util;

use Bee\Http\Context;
use Bee\Http\Request;
use Bee\Http\Response;
use Bee\Di\Container;
use Swoole\Http\Server;

/**
 * Http
 *
 * @package Star\Util
 */
class Http
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @param Application $application
     */
    public function setApp(Application $application)
    {
        $this->app      = $application;
        $this->context  = $application->getContext();
        $this->request  = $this->context->getRequest();
        $this->response = $this->context->getResponse();
    }

    /**
     * 获取全局共享服务
     *
     * @param string $name
     * @return mixed
     */
    protected function getSharedService(string $name)
    {
        return Container::getDefault()->getShared($name);
    }

    /**
     * 获取服务
     *
     * @param string $name
     * @return \Bee\Di\ServiceInterface
     */
    protected function getService(string $name)
    {
        return Container::getDefault()->getService($name);
    }

    /**
     * 投递移步任务
     *  - 每个任务进行编号，保存一份副本至 redis（防止丢失），业务处理结束后删除
     *
     * @param string $class
     * @param array $data
     */
    protected function task(string $class, array $data)
    {
        /** @var Server $server */
        $server  = $this->getSharedService('server');
        // 投递任务
        $success = $server->task(
            [
                'class'  => $class,
                'method' => 'handle',
                'data'   => $data
            ]
        );
        // todo 投递失败，做失败处理
        if ($success === false) {
        }
    }

    /**
     * 向客户端返回 'Content-Type: text/html; charset=utf-8' 内容
     *
     * @param string $html
     * @return string
     */
    protected function html(string $html)
    {
        $this->context->setOutputJson(false);
        $this->context->getResponse()->setHeader('Content-Type', 'text/html; charset=utf-8');
        return $html;
    }

    /**
     * 返回原始内容至客户端
     *
     * @param string $text
     * @return string
     */
    public function raw(string $text)
    {
        $this->context->setOutputJson(false);
        $this->context->getResponse()->setHeader('Content-Type', '	text/plain; charset=utf-8');
        return $text;
    }
}
