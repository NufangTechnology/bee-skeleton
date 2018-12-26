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

// 服务组件配置
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
    $mysql        = new \Phalcon\Db\Adapter\Pdo\Mysql($config['mysql']['default']);
    $eventManager = $di->getShared('eventsManager');

    // 注册数据库环境变量(用于处理断线重连等)
    $eventManager->fire('db:registerEnvVars', $mysql);
    // 注入事件管理器（回调中间件）
    $mysql->setEventsManager($eventManager);

    return $mysql;
});

// 日志服务组件
$di->setShared('service.logger', function () {
    return new Bee\Logger\Adapter\Stream(RUNTIME_PATH);
});

// Http 服务组件
$di->setShared('service.http', function () use ($di) {
    $server = $di->getShared('config.server');
    return new \Star\Util\HttpServer($server['http']);
});