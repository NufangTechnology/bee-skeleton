<?php
namespace Star\Util;

use Phalcon\Config;

/**
 * 消费者代理
 *  - 捕捉异常、错误等
 *
 * @package Star\Util
 * @property Config $global
 */
abstract class RabbitWorker extends \Bee\Mq\Consumer\Rabbit
{
    /**
     * @var Queue
     */
    protected $queue;

    /**
     * RabbitMQ生产者代理
     *
     * @return Queue
     */
    public function rabbit()
    {
        if ($this->queue === null) {
            $this->queue = new Queue;
        }

        return $this->queue;
    }

    /**
     * 任务执行方法体
     *
     * @param \AMQPEnvelope $envelope
     * @param \AMQPQueue $queue
     *
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function consume(\AMQPEnvelope $envelope, \AMQPQueue $queue)
    {
        try {
            // 提取消息数据
            $data   = $this->serializer::unpack($envelope->getBody());

            // 全局请求ID
            $this->global['requestId'] = $data['requestId'] ?? '';
            // 操作用户
            $this->global['userId']    = $data['userId'] ?? '';

            // 执行消息业务
            $this->work($data);

        } catch (\Throwable $e) {
            // 错误记录
            $this->eventsManager->fire("log:handleMqThrowable", $this, $e);
            // 退出当前进程
            $this->workerExit();
        }

        // 消息确认
        $queue->ack($envelope->getDeliveryTag());
    }

    /**
     * 执行队列任务
     *
     * @param array $data
     */
    abstract public function work(array $data);

    /**
     * 消费者异常处理函数
     *
     * @param \Throwable $e
     */
    public function exception(\Throwable $e)
    {
        $this->eventsManager->fire("log:handleMqThrowable", $this, $e);
        // 退出当前进程
        $this->workerExit();
    }

    /**
     * 错误处理
     */
    public function error()
    {
        $this->eventsManager->fire("log:handleMqError", $this, func_get_args());
    }

    public function workerExit()
    {
        // 关闭MySQL连接
        $this->db->close();
        // 关闭Redis连接
        Redis::connect()->close();

        // 退出进程
        parent::workerExit();
    }
}
