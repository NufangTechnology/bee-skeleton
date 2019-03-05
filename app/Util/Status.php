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

    const E_401003 = [401003, '客户端异常']; // token携带客户端版本号与当前客户端版本号不一致
    const E_401004 = [401004, '身份令牌无效'];  // 使用已经被交换过的令牌进行身份验证
    const E_401005 = [401005, '更新令牌失效，请重新登录'];
    const E_401006 = [401006, '客户端异常']; // 身份令牌生成时所在host与当前环境不一致
}
