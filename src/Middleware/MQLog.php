<?php
namespace Star\Middleware;

use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;

class MQLog
{
    public function beforeHandle(Event $event, Micro $micro)
    {}

    public function handle(Event $event, Micro $micro, array $params = [])
    {}

    public function afterHandle(Event $event, Micro $micro, array $params = [])
    {}

    public function throwable()
    {}

    public function error()
    {}

    public function shutdown()
    {}
}