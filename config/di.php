<?php
/**
 * 注入服务组件
 *
 * @var \Bee\Di\Container $di
 */

// cli组件配置
$di->setShared('config.cli', function () {
    return require(CONFIG_PATH . '/cli.php');
});

$di->setShared('config.middleware', function () {
    return require(CONFIG_PATH . '/middleware.php');
});

// 服务组件配置
$di->setShared('config.server', function () {
    return require(CONFIG_PATH . '/server.php');
});

// 加载应用配置
$di->setShared('config.db', function() {
    return require(RUNTIME_PATH . '/build.db.php');
});

// 注入 mysql 组件
$di->setShared('service.mysql', function () use ($di) {
    $config = $di->getShared('config.db');
    return new Bee\Db\MySQL($config['mysql']);
});

// 注入 redis 组件
$di->setShared('service.redis', function () use ($di) {
    $config = $di->getShared('config.db');
    return new Bee\Db\Redis($config['redis']);
});

// 注入 logger （日志）组件
$di->setShared('service.logger', function () use ($di) {
    $config = $di->getShared('config.server');
    return new \Bee\Logger\Adapter\SeasLog($config['logger']);
});

// 注入 Http 服务组件
$di->setShared('service.http', function () use ($di) {
    $config = $di->getShared('config.server');
    return new \Star\Util\HttpServer($config['http']);
});

// 多进程 worker 配置
$di->setShared('config.worker', function () {
    return require(CONFIG_PATH . '/worker.php');
});

// 注入多进程 worker程服务
$di->setShared('service.worker', function () use ($di) {
    $config = $di->getShared('config.server');
    return new \Star\Job\Master($config['worker']);
});