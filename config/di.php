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
 *  系统服务配置
 * ----------------------------------------------------------------------------------------
 */

// 服务组件配置
$di->setShared('config.server', function () {
    return require(CONFIG_PATH . '/server.php');
});

// 注入 Http 服务组件
$di->setShared('service.http', function () use ($di) {
    $config = $di->getShared('config.server');
    return new \Star\Util\HttpServer($config['http']);
});

// 注入 logger （日志）组件
$di->setShared('service.logger', function () use ($di) {
    $config = $di->getShared('config.server');
    return new \Bee\Logger\Adapter\SeasLog($config['logger']);
});


/**
 * ----------------------------------------------------------------------------------------
 *  路由服务配置
 * ----------------------------------------------------------------------------------------
 */

// 注入路由配置
$di->setShared('route.http', function () {
    return require(CONFIG_PATH . '/route.php');
});

// 注入路由组件
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


/**
 * ----------------------------------------------------------------------------------------
 *  多进程服务配置
 * ----------------------------------------------------------------------------------------
 */

// 多进程 worker 配置
$di->setShared('config.job', function () {
    return require(CONFIG_PATH . '/job.php');
});

// 注入多进程 worker程服务
$di->setShared('service.job', function () use ($di) {
    $config = $di->getShared('config.server');
    return new \Star\Job\Master($config['job']);
});