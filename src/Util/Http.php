<?php
namespace Star\Util;

use Bee\Injectable;
use Phalcon\Config;

/**
 * Http
 *
 * @package Star\Util
 * @property Config $global
 */
class Http extends Injectable
{
    /**
     * @var Queue
     */
    protected $queue;

    /**
     * RabbitMQ生产者代理
     *
     * @return Queue
     */
    public function rabbit()
    {
        if ($this->queue === null) {
            $this->queue = new Queue;
        }

        return $this->queue;
    }
}
