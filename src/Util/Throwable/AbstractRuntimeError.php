<?php
namespace Star\Util\Throwable;

/**
 * 应用错误基类
 *
 * @package Star\Util\Throwable
 */
abstract class AbstractRuntimeError extends \Error
{
    /**
     * 异常错误级别码
     *
     * @var int
     */
    protected $level = 0;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var int
     */
    protected $line;

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
     * @param string $file
     * @param string $line
     */
    public function __construct(string $message = '', int $code = 0, $file = '', $line = '')
    {
        $this->message = $message;
        $this->code    = $code;
        $this->file    = $file;
        $this->line    = $line;

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

    /**
     * @return string
     */
    public function getLevel(): string
    {
//        return $this->level;
        return '';
    }
}
