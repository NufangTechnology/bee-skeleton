<?php
/**
 * 中间件绑定配置文件
 *
 * @var \Phalcon\Di $di
 * @var \Phalcon\Events\Manager $eventsManager
 */

// 获取事件管理器
$eventsManager = $di->getShared('eventsManager');

// 注册跨域中间件
$eventsManager->attach('micro', new \Star\Middleware\CORS);
// 注册用户身份认证中间件
$eventsManager->attach('micro', new \Star\Middleware\Auth);
// 注册路由挂载中间件
$eventsManager->attach('micro', new \Star\Middleware\Route);
// 注册Response中间件 - 处理返回内容体
$eventsManager->attach('micro', new \Star\Middleware\Response);
// MySQL数据库中间件
$eventsManager->attach('db', new \Star\Middleware\MySQL);
// 日志中间件
$eventsManager->attach('log', new \Star\Middleware\Log);
