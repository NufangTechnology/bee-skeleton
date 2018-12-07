<?php
/**
 * 注入服务组件
 *
 * @var \Phalcon\Di $di
 * @var \Phalcon\Mvc\Micro $micro
 */

// 全局数据共享组件
$di->setShared('global', function () {
    return new Phalcon\Config();
});

// cli组件配置
$di->setShared('config.cli', function () {
    return require(CONFIG_PATH . '/cli.php');
});

// http组件配置
$di->setShared('config.server', function () {
    return require(CONFIG_PATH . '/server.php');
});

// 加载应用配置
$di->setShared('config.db', function() {
    return require(RUNTIME_PATH . '/build.db.php');
});

// 默认数据库组件
$di->setShared('db', function() use ($di) {
    $config       = $di->get('config.db');
    $eventManager = $di->getShared('eventsManager');
    $mysql        = new \Phalcon\Db\Adapter\Pdo\Mysql($config['mysql']['default']);

    // 注册数据库环境变量(用于处理断线重连等)
    $eventManager->fire('db:registerEnvVars', $mysql);
    // 注入事件管理器（回调中间件）
    $mysql->setEventsManager($eventManager);

    return $mysql;
});

// 默认redis组件
$di->setShared('db.redis.default', function () use ($di) {
    // 读取redis配置
    $config = $di->get('config.db');
    $config = $config['redis']['default'];

    try {
        $redis = new \Redis();

        // 根据配置启用长连接
        if (empty($config['persistent'])) {
            $redis->pconnect($config['host'], $config['port']);
        } else {
            $redis->connect($config['host'], $config['port']);
        }

        // 密码
        if (!empty($config['auth'])) {
            $success = $redis->auth($config['auth']);
            if (!$success) {
                throw new \Star\Util\Exception\RedisException('Redis密码错误', 500, [], $config);
            }
        }

        return $redis;
    } catch (Exception $e) {
        throw new \Star\Util\Exception\RedisException($e->getMessage(), 500, [], $config);
    }
});

// Http 服务组件
$di->setShared('service.http', function () use ($di) {
    $server = $di->getShared('config.server');
    return new \Star\Util\HttpServer($server['http']);
});