<?php
namespace Star\Util;

use Star\Util\Throwable\AbstractRuntimeException;
use Star\Util\Throwable\ClientParamsInvalidException;
use Star\Util\Throwable\DbDeleteException;
use Star\Util\Throwable\DbInsertException;
use Star\Util\Throwable\DbUpdateException;
use Star\Util\Throwable\ResourceNotFoundException;
use Star\Util\Throwable\UnauthorizedException;
use Star\Util\Throwable\UrlNotFoundException;

/**
 * 异常统一组织类
 *
 * @package Eye\Util
 */
class ThrowException
{
    /**
     * 客户端参数错误
     *
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws AbstractRuntimeException
     */
    public static function clientParamInvalid(array $status, array $data = [], array $args = [])
    {
        throw new ClientParamsInvalidException($status[1], $status[0], $data, $args);
    }

    /**
     * 记录删除失败
     *
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws AbstractRuntimeException
     */
    public static function dbDeleteFailed(array $status, array $data = [], array $args = [])
    {
        throw new DbDeleteException($status[1], $status[0], $data, $args);
    }

    /**
     * 记录创建失败
     *
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws AbstractRuntimeException
     */
    public static function dbInsertFailed(array $status, array $data = [], array $args = [])
    {
        throw new DbInsertException($status[1], $status[0], $data, $args);
    }

    /**
     * 记录创建失败
     *
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws AbstractRuntimeException
     */
    public static function dbUpdateFailed(array $status, array $data = [], array $args = [])
    {
        throw new DbUpdateException($status[1], $status[0], $data, $args);
    }

    /**
     * 请求的资源不存在
     *
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws AbstractRuntimeException
     */
    public static function resourceNotFound(array $status, array $data = [], array $args = [])
    {
        throw new ResourceNotFoundException($status[1], $status[0], $data, $args);
    }

    /**
     * 无权限
     *
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws AbstractRuntimeException
     */
    public static function unauthorized(array $status, array $data = [], array $args = [])
    {
        throw new UnauthorizedException($status[1], $status[0], $data, $args);
    }

    /**
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws AbstractRuntimeException
     */
    public static function urlNotFound(array $status, array $data = [], array $args = [])
    {
        throw new UrlNotFoundException($status[1], $status[0], $data, $args);
    }
}
