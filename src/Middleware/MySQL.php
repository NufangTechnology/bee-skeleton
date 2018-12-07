<?php
namespace Star\Middleware;

use Phalcon\Db\Adapter;
use Phalcon\Di;
use Phalcon\Events\Event;

/**
 * MySQL 中间件
 * @package Eye\Middleware
 */
class MySQL
{
    /**
     * 注册MySQL环境变量数据
     *  - 客户端空闲断开时间
     *
     * @param Event $event
     * @param Adapter\Pdo\Mysql $mysql
     */
    public function registerEnvVars(Event $event, Adapter\Pdo\Mysql $mysql)
    {
        $global  = Di::getDefault()->getShared('global');

        // 查询数据库主动断开连接时间
        $result  = $mysql->query("SHOW VARIABLES LIKE 'wait_timeout'");
        $result  = $result->fetchArray();
        // 客户端空闲超时断开时间
        $timeout = $result['Value'] - 1;
        $time    = time();

        // 保存超时时间至全局变量
        $global['MYSQL_TIMEOUT']    = $timeout;
        $global['MYSQL_CLOSE_TIME'] = $time + $timeout;
    }

    /**
     * MySQL断线重连
     *  - 检查超时时间，如果超时，尝试重连
     *  - 更新空闲超时时间
     *
     * @param Event $event
     * @param Adapter $adapter
     */
    public function beforeQuery(Event $event, Adapter $adapter)
    {
        $global      = Di::getDefault()->getShared('global');
        $currentTime = time();

        // 断线重连
        if ($global['MYSQL_CLOSE_TIME'] <= $currentTime) {
            $adapter->connect();
        }

        // 执行了查询，更新空闲超时时间
        $global['MYSQL_CLOSE_TIME'] = $global['MYSQL_TIMEOUT'] + $currentTime;
    }
}