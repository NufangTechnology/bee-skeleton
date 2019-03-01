<?php
namespace Bee;

/**
 * 应用异常基类
 *
 * @package Star\Util\Throwable
 */
abstract class RuntimeException extends \Exception
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * 运行参数
     *
     * @var array
     */
    protected $args = [];

    /**
     * Throwable constructor.
     *
     * @param string $message
     * @param int $code
     * @param array $data
     * @param array $args
     */
    public function __construct(string $message, int $code = 0, array $data = [], array $args = [])
    {
        $this->message = $message;
        $this->code    = $code;
        $this->data    = $data;
        $this->args    = $args;

        parent::__construct($message, $code);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }
}