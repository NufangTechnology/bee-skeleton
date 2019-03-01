<?php
// 中间件绑定配置文件
return [
    \Star\Middleware\CORS::class,
    \Star\Middleware\Route::class,
    \Star\Middleware\Auth::class,
    \Star\Middleware\Dispatch::class,
];
