<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false;
}

$_GET['_url'] = $_SERVER['PHP_SELF'];

// 禁止显示错误
ini_set('display_errors', 0);
// 脚本最大执行时间
ini_set('max_execution_time', 30);

// 设置locale时区
date_default_timezone_set("PRC");

// 系统根路径
define('ROOT_PATH', __DIR__);
// 运行时目录
define('RUNTIME_PATH', ROOT_PATH . '/runtime');
// 配置目录
define('CONFIG_PATH', ROOT_PATH . '/config');

//// 引入组件库
require __DIR__ . '/vendor/autoload.php';

try {

    // 创建容器
    $di = new \Phalcon\Di();

    // 注册系统默认组件
    $di->setShared('eventsManager',Phalcon\Events\Manager::class);
    $di->setShared('modelsManager',Phalcon\Mvc\Model\Manager::class);
    $di->setShared('modelsMetadata', \Phalcon\Mvc\Model\MetaData\Memory::class);

    // 加载框架自定义组件
    require(CONFIG_PATH . '/di.php');
    // 加载中间件
    require(CONFIG_PATH . '/middleware.php');

    // 实例化应用
    $micro = new \Star\Util\Micro($di);

    // 注入request于response组件
    $di->setShared('request', \Phalcon\Http\Request::class);
    $di->setShared('response', \Phalcon\Http\Response::class);
    // 注册系统默认组件
    $di->setShared('filter',\Phalcon\Filter::class);
    $di->setShared('security',\Phalcon\Security::class);
    $di->setShared('router', \Phalcon\Mvc\Router::class);

    // 注册错误处理方法
    register_shutdown_function(function () use ($micro) {
        $micro->eventsManager->fire("log:handleShutdown", $micro);
    });

    // micro注入时间管理器
    $micro->setEventsManager($di->getShared('eventsManager'));

    // 加载路由
    $routes = require(CONFIG_PATH . '/routes.php');
    foreach ($routes as $route) {
        $micro->mount($route);
    }

    $micro->handle();

} catch (\Throwable $e) {
    echo $e->getMessage();
}