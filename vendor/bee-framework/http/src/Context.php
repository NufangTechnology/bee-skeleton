<?php
namespace Bee\Http;

use Swoole\Http\Request as SwooleHttpRequest;
use Swoole\Http\Response as SwooleHttpResponse;

/**
 * HTTP 请求处理上下文
 *
 * @package Bee\Http
 */
class Context
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var mixed
     */
    private $content;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var array
     */
    private $logs = [];

    /**
     * @var bool
     */
    private $outputJson = true;

    /**
     * Context
     *
     * @param SwooleHttpRequest $request
     * @param SwooleHttpResponse $response
     */
    public function __construct(SwooleHttpRequest $request, SwooleHttpResponse $response)
    {
        $this->request  = new Request;
        $this->response = new Response;

        $this->request->withSource($request);
        $this->response->withSource($response);
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * @param $value
     */
    public function setContent($value): void
    {
        $this->content = $value;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param $log
     */
    public function setLog($log)
    {
        $this->logs[] = $log;
    }

    /**
     * @return array
     */
    public function getLogs(): array
    {
        return $this->logs;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function isOutputJson(): bool
    {
        return $this->outputJson;
    }

    /**
     * @param bool $outputJson
     */
    public function setOutputJson(bool $outputJson): void
    {
        $this->outputJson = $outputJson;
    }

    /**
     * @return false|string
     */
    public function __toString()
    {
        $data = json_encode(
            [
                'request_uri' => $this->request->getServer('request_uri'),
                'method'      => $this->request->getServer('request_method'),
                'headers'      => [
                    'Host'             => $this->request->getHeader('Host'),
                    'Content-Type'     => $this->request->getHeader('Content-Type'),
                    'User-Agent'       => $this->request->getHeader('User-Agent'),
                    'Referer'          => $this->request->getHeader('Referer'),
                    'X-Api-Version'    => $this->request->getHeader('X-Api-Version'),
                    'X-Client-Version' => $this->request->getHeader('X-Client-Version'),
                    'X-Mini-Version'   => $this->request->getHeader('X-Mini-Version'),
                    'X-System-Version' => $this->request->getHeader('X-System-Version'),
                    'X-Token'          => $this->request->getHeader('X-Token'),
                ],
                'get'         => $this->request->getQuery(),
                'body'        => $this->request->getJsonRawBody(),
                'context'     => $this->content,
                'data'        => $this->data,
                'logs'        => $this->logs
            ],
            JSON_UNESCAPED_UNICODE
        );

        if (empty($data)) {
            $data = 'Context转换为string失败';
        }

        return $data;
    }
}
