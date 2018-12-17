<?php
namespace Star\Middleware;

use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;
use Star\Util\Throwable\AbstractError;
use Star\Util\Throwable\AbstractException;

class HttpLog
{
    public function beforeHandle(Event $event, Micro $micro)
    {
        return [
            'label'       => 'http:before',
            'unique_id'   => '',
            'user_id'     => '',
            'level'        => 'info',
            'timestamp'   => '',
            'url'         => '',
            'ip'          => '',
            'http_method' => '',
            'referrer'    => '',
            'get'         => [],
            'body'        => [],
            'server'      => [],
        ];
    }

    public function handle(Event $event, Micro $micro, array $params = [])
    {
        return [
            'label'       => 'http:running',
            'unique_id'   => '',
            'user_id'     => '',
            'level'        => 'info',
            'timestamp'   => '',
            'params'      => $params
        ];
    }

    public function afterHandle(Event $event, Micro $micro, array $params = [])
    {
        return [
            'label'       => 'http:after',
            'unique_id'   => '',
            'user_id'     => '',
            'level'        => 'info',
            'timestamp'   => '',
            'params'      => $params
        ];
    }

    public function throwException(AbstractException $exception)
    {
        return [
            'label'     => 'http:exception',
            'unique_id' => '',
            'user_id'   => '',
            'level'      => $exception->getLevel(),
            'timestamp' => '',
            'message'   => $exception->getMessage(),
            'code'      => $exception->getCode(),
            'file'      => $exception->getFile(),
            'line'      => $exception->getLine(),
            'data'      => $exception->getData(),
            'args'      => $exception->getArgs(),
        ];
    }

    public function throwError(AbstractError $error)
    {
        return [
            'label'     => 'http:error',
            'unique_id' => '',
            'user_id'   => '',
            'level'      => $error->getLevel(),
            'timestamp' => '',
            'message'   => $error->getMessage(),
            'code'      => $error->getCode(),
            'file'      => $error->getFile(),
            'line'      => $error->getLine(),
            'data'      => $error->getData(),
            'args'      => $error->getArgs(),
        ];
    }

    public function shutdown()
    {
        return [
            'label'       => 'http:shutdown',
            'unique_id'   => '',
            'user_id'     => '',
            'level'        => 'EMERGENCY',
            'timestamp'   => '',
        ];
    }
}
