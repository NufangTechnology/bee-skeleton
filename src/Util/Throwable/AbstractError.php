<?php
namespace Star\Util\Throwable;

/**
 * 应用错误基类
 *
 * @package Star\Util\Throwable
 */
abstract class AbstractError extends \Error
{
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
}
