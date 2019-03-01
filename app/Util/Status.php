<?php
namespace Star\Util;

/**
 * 应用状态码
 *
 * @package Star\Http\Core
 */
class Status extends \Bee\Status
{
    const E_400000 = [400000, '参数错误，请检查后重试'];
    const E_400001 = [400001, '客户端类型错误'];
    const E_400002 = [400002, '未找到对应小程序配置'];
    const E_400003 = [400003, 'iv长度错误'];
    const E_400004 = [400004, 'session_key获取失败'];
    const E_400005 = [400005, '微信返回数据解密失败'];
    const E_400006 = [400006, '获取哦access token失败'];
}
