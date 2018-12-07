<?php
namespace Star\Util;

use Star\Util\Exception\ClientParamInvalidException;
use Star\Util\Exception\DbInsertFailedException;
use Star\Util\Exception\DisableException;
use Star\Util\Exception\ResourceNotFoundException;
use Star\Util\Exception\UnauthorizedException;
use Star\Util\Exception\UrlNotFoundException;

/**
 * Exception
 *
 * @package Eye\Util
 */
class Exception extends \Exception
{
    /**
     * @var array
     */
    public $data = [];

    /**
     * 运行参数
     *
     * @var array
     */
    public $args = [];

    /**
     * Exception constructor.
     *
     * @param string $message
     * @param int $code
     * @param array $data
     * @param array $args
     */
    public function __construct(string $message = '', int $code = 0, array $data = [], array $args = [])
    {
        $this->message = $message;
        $this->code    = $code;
        $this->data    = $data;
        $this->args    = $args;
    }

    /**
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws UrlNotFoundException
     */
    static public function urlNotFound(array $status, array $data = [], array $args = [])
    {
        throw new UrlNotFoundException($status[1], $status[0], $data, $args);
    }

    /**
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws ResourceNotFoundException
     */
    static public function resourceNotFound(array $status, array $data = [], array $args = [])
    {
        throw new ResourceNotFoundException($status[1], $status[0], $data, $args);
    }

    /**
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws ClientParamInvalidException
     */
    static public function clientParamInvalid(array $status, array $data = [], array $args = [])
    {
        throw new ClientParamInvalidException($status[1], $status[0], $data, $args);
    }

    /**
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws UnauthorizedException
     */
    static public function unauthorized(array $status, array $data = [], array $args = [])
    {
        throw new UnauthorizedException($status[1], $status[0], $data, $args);
    }

    /**
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws DisableException
     */
    static public function disable(array $status, array $data = [], array $args = [])
    {
        throw new DisableException($status[1], $status[0], $data, $args);
    }

    /**
     * @param array $status
     * @param array $data
     * @param array $args
     * @throws DbInsertFailedException
     */
    static public function dbInsertFailed(array $status, array $data = [], array $args = [])
    {
        throw new DbInsertFailedException($status[1], $status[0], $data, $args);
    }
}
