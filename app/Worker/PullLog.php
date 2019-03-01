<?php
namespace Star\Worker;

use Star\Cache\ListApiErrorLog;
use Swoole\Process;
use Bee\Process\Worker;
use Swoole\Timer;
use Star\Model\Demo as ApiErrorLogModel;

/**
 * 从 redis 拉取 filebeat 采集的日志
 *
 * @package Star\Worker
 */
class PullLog extends Worker
{
    /**
     * @param Process $process
     * @param int $ppid
     */
    public function handle(Process $process, $ppid)
    {
        go(function () {
            $apiErrorLogCache = new ListApiErrorLog;
            $apiErrorLogModel = new ApiErrorLogModel;

            Timer::tick(10, function () use ($apiErrorLogCache, $apiErrorLogModel) {
                try {

                    $data = $apiErrorLogCache->pop();
                    // 没有数据，跳出
                    if (empty($data)) {
                        return;
                    }

                    $data = json_decode($data, true);
                    $log  = explode(' | ', $data['message']);
                    $shot = json_decode($log[5], true);

                    // 创建日志记录至数据库
                    $apiErrorLogModel->create(
                        [
                            'logAt'    => $log[2],
                            'name'     => $shot['name'] ?? 'undefined',
                            'level'    => $log[3],
                            'url'      => $shot['request_uri'] ?? '',
                            'method'   => $shot['method'] ?? '',
                            'message'  => $shot['logs'][0]['message'] ?? '',
                            'code'     => $shot['logs'][0]['code'] ?? '',
                            'ip'       => '',
                            'source'   => $data['source'] ?? '',
                            'memory'   => $log[4],
                            'pid'      => $log[1],
                            'headers'  => json_encode($shot['headers'], JSON_UNESCAPED_UNICODE),
                            'trace'    => json_encode($shot['logs'], JSON_UNESCAPED_UNICODE),
                            'response' => json_encode($shot['runtime'], JSON_UNESCAPED_UNICODE),
                        ]
                    );

                } catch (\Throwable $e) {
                    PR($e);
//                    (new Application)->collectionException($e);
                }
            });
        });
    }
}