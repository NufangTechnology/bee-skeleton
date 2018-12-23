<?php
/*
|--------------------------------------------------------------------------
| 系统内置助手函数
|--------------------------------------------------------------------------
*/

/**
 * @var \Phalcon\Di $di
 */

/**
 * 格式化内容数据
 *
 * @param $bytes
 * @return string
 */
function formatBytes($bytes)
{
    $bytes = (int) $bytes;

    if ($bytes > 1024 * 1024) {
        return round($bytes / 1024 / 1024, 2).' MB';
    } elseif ($bytes > 1024) {
        return round($bytes / 1024, 2).' KB';
    }

    return $bytes . ' B';
}