<?php

/**
 *
SeasLog提供了下列预设变量，可以直接使用在日志模板中，将在日志最终生成时替换成对应值。

%L - Level 日志级别。
%M - Message 日志信息。
%T - DateTime 如2017-08-16 19:15:02，受seaslog.default_datetime_format影响。
%t - Timestamp 如1502882102.862，精确到毫秒数。
%Q - RequestId 区分单次请求，如没有调用SeasLog::setRequestId($string)方法，则在初始化请求时，采用内置的static char *get_uniqid()方法生成的惟一值。
%H - HostName 主机名。
%P - ProcessId 进程ID。
%D - Domain:Port 域名:口号，如www.cloudwise.com:8080; Cli模式下为cli。
%R - Request URI 请求URI，如/app/user/signin; Cli模式下为入口文件，如CliIndex.php。
%m - Request Method 请求类型，如GET; Cli模式下为执行命令，如/bin/bash。
%I - Client IP 来源客户端IP; Cli模式下为local。取值优先级为：HTTP_X_REAL_IP > HTTP_X_FORWARDED_FOR > REMOTE_ADDR
%F - FileName:LineNo 文件名:行号，如UserService.php:118。
%U - MemoryUsage 当前内容使用量，单位byte。调用zend_memory_usage。
%u - PeakMemoryUsage 当前内容使用峰值量，单位byte。调用zend_memory_peak_usage。
%C - TODO Class::Action 类名::方法名，如UserService::getUserInfo。
 */

// 日志服务配置
$config = [
    'base_dir'        => '',
    'folder_name'     => 'log',
    'template'        => '%H | %P | %T | %L | %U | %u | %t | %M',
];

$startTime = microtime(true);


$id = SeasLog::getRequestID();


$id_ = SeasLog::getRequestID();

$init = ini_get_all('seaslog');

ini_set('seaslog.default_template', $config['template']);

$init_after = ini_get_all('seaslog');

print_r($init_after);

