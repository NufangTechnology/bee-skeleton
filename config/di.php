<?php
/**
 * 注入服务组件
 *
 * @var \Bee\Di\Container $di
 */

/**
 * ----------------------------------------------------------------------------------------
 *  公用配置
 * ----------------------------------------------------------------------------------------
 */

// cli组件配置
$di->setShared('config.console', function () {
    return require(CONFIG_PATH . '/console.php');
});

// 中间件配置
$di->setShared('config.middleware', function () {
    return require(CONFIG_PATH . '/middleware.php');
});


/**
 * ----------------------------------------------------------------------------------------
 *  系统服务(http, logger)
 * ----------------------------------------------------------------------------------------
 */

// 配置
$di->setShared('config.server', function () {
    return require(CONFIG_PATH . '/server.php');
});

// Http 服务
$di->setShared('service.http', function () use ($di) {
    $config = $di->getShared('config.server');
    return new \Star\Util\HttpServer($config['http']);
});

// 日志服务
$di->setShared('service.logger', function () use ($di) {
    $config = $di->getShared('config.server');
    return new \Bee\Logger\Adapter\SeasLog($config['logger']);
});


/**
 * ----------------------------------------------------------------------------------------
 *  路由服务
 * ----------------------------------------------------------------------------------------
 */

// 路由配置
$di->setShared('route.http', function () {
    return require(CONFIG_PATH . '/route.php');
});

// 路由服务
$di->setShared('service.router', function () use ($di) {
    // 路由规则
    $rules  = $di->getShared('route.http');
    // 示例化并挂载路由规则
    $router = new \Bee\Router\Router();
    $router->map($rules);

    return $router;
});


/**
 * ----------------------------------------------------------------------------------------
 *  数据库服务配置
 * ----------------------------------------------------------------------------------------
 */

// 数据库配置
$di->setShared('config.db', function() {
    return require(RUNTIME_PATH . '/build.db.php');
});

// mysql 服务
$di->setShared('service.mysql', function () use ($di) {
    $config = $di->getShared('config.db');
    return new Bee\Db\MySQL($config['mysql']);
});

// redis 服务
$di->setShared('service.redis', function () use ($di) {
    $config = $di->getShared('config.db');
    return new Bee\Db\Redis($config['redis']);
});


/**
 * ----------------------------------------------------------------------------------------
 *  多进程服务配置
 * ----------------------------------------------------------------------------------------
 */

// 配置
$di->setShared('config.job', function () {
    return require(CONFIG_PATH . '/job.php');
});

// 多进程服务
$di->setShared('service.job', function () use ($di) {
    $config = $di->getShared('config.server');
    return new \Star\Job\Master($config['job']);
});