<?php
namespace Star\Util\Throwable;

/**
 * 应用异常基类
 *
 * @package Star\Util\Throwable
 */
abstract class AbstractException extends \Exception
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
     * Throwable constructor.
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

        parent::__construct($message, $code);
    }
}