<?php
namespace Star\Util;

/**
 * 应用状态码
 *
 * @package Pay\Http\Core
 */
class Status extends \Bee\Status
{
    const E_400000 = [400000, '参数错误，请检查后重试'];
    const E_400001 = [400001, '应用创建失败，请检查后重试'];
    const E_400002 = [400002, '应用token生成失败'];
    const E_400003 = [400003, '日志创建失败'];
    const E_400010 = [400010, '应用token不能为空'];
    const E_400011 = [400011, '应用token解析失败'];

    const E_404002 = [404002, '账号或密码错误，请检查后重试'];
    const E_404003 = [404003, '您已被禁用，请联系管理员'];
}
