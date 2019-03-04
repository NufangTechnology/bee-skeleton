<?php
namespace Bee;

/**
 * Exception
 *
 * @package Ant
 */
class Exception extends \Exception
{
    /**
     * @var string
     */
    protected $level = 'critical';

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $runtime = [];

    /**
     * Throwable constructor.
     *
     * @param string $message
     * @param int $code
     * @param array $data
     * @param array $runtime
     */
    public function __construct(string $message, int $code = 0, array $data = [], array $runtime = [])
    {
        $this->message = $message;
        $this->code    = $code;
        $this->data    = $data;
        $this->runtime = $runtime;

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
     * @return mixed
     */
    public function getRuntime()
    {
        return $this->runtime;
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
        $trace = $this->getTrace();

        return [
            'message' => $this->message,
            'code'    => $this->code,
            'class'   => get_class($this),
            'line'    => $trace[0]['line'] ?? 0,
            'file'    => $trace[0]['file'] ?? '',
            'args'    => $trace[0] ?? [],
            'data'    => $this->data,
            'runtime' => $this->runtime,
        ];
    }
}
