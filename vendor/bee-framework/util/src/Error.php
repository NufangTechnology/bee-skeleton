<?php
namespace Bee;

/**
 * Error
 *
 * @package Bee
 */
class Error extends \Error
{
    /**
     * @var string
     */
    protected $level = 'error';

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
     * Error constructor.
     *
     * @param string $message
     * @param int $code
     * @param int $line
     * @param string $file
     */
    public function __construct(string $message = "", int $code = 0, int $line = 0, string $file = '')
    {
        $this->message = $message;
        $this->code    = $code;
        $this->line    = $line;
        $this->file    = $file;

        parent::__construct($message, $code);
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * 异常内容转换为数组
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'code'    => $this->code,
            'line'    => $this->line,
            'class'   => get_class($this),
            'file'    => $this->file,
            'args'    => [],
        ];
    }
}
