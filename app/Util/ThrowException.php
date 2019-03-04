<?php
namespace Star\Util;

/**
 * 异常统一组织类
 *
 * @package Eye\Util
 */
class ThrowException extends \Bee\RuntimeException
{
    /**
     * 服务不可用
     *
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws ThrowException
     */
    public static function unavailable(array $status = Status::E_500000, array $data = [], array $args = [])
    {
        throw new self($status[1], $status[0], $data, $args);
    }

    /**
     * URL未找到
     *
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws ThrowException
     */
    public static function urlNotFound(array $status = Status::E_404000, array $data = [], array $args = [])
    {
        throw new self($status[1], $status[0], $data, $args);
    }

    /**
     * 请求的资源不存在
     *
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws ThrowException
     */
    public static function resourceNotFound(array $status = Status::E_404001, array $data = [], array $args = [])
    {
        throw new self($status[1], $status[0], $data, $args);
    }

    /**
     * 客户端参数错误
     *
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws ThrowException
     */
    public static function clientParamInvalid(array $status = Status::E_400000, array $data = [], array $args = [])
    {
        throw new self($status[1], $status[0], $data, $args);
    }

    /**
     * 记录删除失败
     *
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws ThrowException
     */
    public static function dbDeleteFailed(array $status, array $data = [], array $args = [])
    {
        throw new self($status[1], $status[0], $data, $args);
    }

    /**
     * 记录创建失败
     *
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws ThrowException
     */
    public static function dbInsertFailed(array $status = Status::E_500001, array $data = [], array $args = [])
    {
        throw new self($status[1], $status[0], $data, $args);
    }

    /**
     * 记录创建失败
     *
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws ThrowException
     */
    public static function dbUpdateFailed(array $status, array $data = [], array $args = [])
    {
        throw new self($status[1], $status[0], $data, $args);
    }

    /**
     * 无权限
     *
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws ThrowException
     */
    public static function unauthorized(array $status = Status::E_401002, array $data = [], array $args = [])
    {
        throw new self($status[1], $status[0], $data, $args);
    }

    /**
     * 第三方授权信息获取失败
     *
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws ThrowException
     */
    public static function authFailed(array $status = Status::E_500000, array $data = [], array $args = [])
    {
        throw new self($status[1], $status[0], $data, $args);
    }
}