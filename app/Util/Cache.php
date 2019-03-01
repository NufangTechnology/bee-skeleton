<?php
namespace Star\Util;

use Bee\Di\Container;

/**
 * Redis缓存基类
 *
 * @package Star\Util
 */
abstract class Cache
{
    /**
     * @var \Bee\Db\Redis
     */
    protected $con;

    /**
     * @var string
     */
    protected $dbName = 'service.redis';

    /**
     * @var int
     */
    protected $index = 0;

    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * Cache constructor.
     */
    public function __construct()
    {
        $this->con = Container::getDefault()->getShared($this->dbName);
    }
}
