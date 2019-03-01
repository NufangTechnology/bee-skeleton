<?php
namespace Bee\Http;

use Bee\Http\Request\File;
use Bee\Http\Request\FileInterface;

/**
 * Request
 *
 * @package Ant\Http
 */
class Request implements RequestInterface
{
    /**
     * @var \Swoole\Http\Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $putCache;

    /**
     * 数据源
     *
     * @param \Swoole\Http\Request $request
     */
    public function withSource(\Swoole\Http\Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param string|null $name
     * @param null $filters
     * @param null $defaultValue
     * @param bool $notAllowEmpty
     * @return mixed
     */
    public function getPost(string $name = null, $filters = null, $defaultValue = null, bool $notAllowEmpty = false)
    {
        if ($this->request->post) {
            return $this->getHelper($this->request->post, $name, $filters, $defaultValue, $notAllowEmpty);
        } else {
            return $this->getPut($name, $filters, $defaultValue, $notAllowEmpty);
        }
    }

    /**
     * @param string|null $name
     * @param null $filters
     * @param null $defaultValue
     * @param bool $notAllowEmpty
     * @return mixed
     */
    public function getPut(string $name = null, $filters = null, $defaultValue = null, bool $notAllowEmpty = false)
    {
        $put = $this->putCache;

        if (!is_array($put)) {
            $contentType = $this->getContentType();
            if (is_string($contentType) && stripos($contentType, 'json') !== false) {
                $put = $this->getJsonRawBody(true);
                if (!is_array($put)) {
                    $put = [];
                }
            } else {
                $put = [];
                parse_str($this->getRawBody(), $put);
            }

            $this->putCache = $put;
        }

        return $this->getHelper($put, $name, $filters, $defaultValue, $notAllowEmpty);
    }

    /**
     * @param string|null $name
     * @param null $filters
     * @param null $defaultValue
     * @param bool $notAllowEmpty
     * @return mixed
     */
    public function getQuery(string $name = null, $filters = null, $defaultValue = null, bool $notAllowEmpty = false)
    {
        return $this->getHelper($this->request->get, $name, $filters, $defaultValue, $notAllowEmpty);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getServer(string $name = '')
    {
        return $this->getHelper($this->request->server, strtolower($name));
    }

    /**
     * @param string $header
     * @return string
     */
    public function getHeader(string $header = '')
    {
        return $this->getHelper($this->request->header, strtolower($header));
    }

    /**
     * Helper to get data from source, applying filters if needed.
     * If no parameters are given the source is returned.
     *
     * @param array $source
     * @param string|null $name
     * @param null $filters
     * @param null $defaultValue
     * @param bool $notAllowEmpty
     * @return mixed|null
     */
    protected function getHelper($source, string $name = null, $filters = null, $defaultValue = null, bool $notAllowEmpty = false)
    {
        if ($name == null) {
            return $source;
        }

        $value = $source[$name] ?? '';

        if ($value == null) {
            return $defaultValue;
        }

        if (is_callable($filters)) {
            $value = $filters($value);
        }

        if (empty($value) && $notAllowEmpty) {
            return $defaultValue;
        }

        return $value;
    }

    /**
     * Gets HTTP schema (http/https)
     */
    public function getScheme(): string
    {
        $https = $this->getServer('HTTPS');

        if ($https) {
            if ($https == 'off') {
                $scheme = 'http';
            } else {
                $scheme = 'https';
            }
        } else {
            $scheme = 'http';
        }

        return $scheme;
    }

    /**
     * Checks whether request has been made using ajax. Checks if $_SERVER["HTTP_X_REQUESTED_WITH"] === "XMLHttpRequest"
     */
    public function isAjax(): bool
    {
        return isset($this->request->header['http_x_requested_with'])
            && $this->request->header['http_x_requested_with'] == 'XMLHttpRequest';
    }

    /**
     * Checks whether request has been made using any secure layer
     */
    public function isSecureRequest(): bool
    {
        return $this->getScheme() === 'https';
    }

    /**
     * Gets HTTP raw request body
     */
    public function getRawBody(): string
    {
        return $this->request->rawContent();
    }

    /**
     * 以json格式获取源数据
     *
     * @param bool $associative
     * @return array|bool|mixed|\stdClass
     */
    public function getJsonRawBody($associative = false)
    {
        $rawBody = $this->getRawBody();

        if (!is_string($rawBody)) {
            return false;
        }

        return json_decode($rawBody, $associative);
    }

    /**
     * Gets active server address IP
     */
    public function getServerAddress(): string
    {
        return $this->request->server['server_addr'] ?? 'localhost';
    }

    /**
     * Gets active server name
     */
    public function getServerName(): string
    {
        return $this->request->server['server_name'] ?? 'localhost';
    }

    /**
     * Gets host name used by the request
     */
    public function getHttpHost(): string
    {
        $host = $this->getHeader('HOST');

        if ($host) {
            $info = explode(':', $host);

            return $info[0];
        }

        return (string)$host;
    }

    /**
     * Gets information about the port on which the request is made
     */
    public function getPort(): int
    {
        return (int)$this->request->server['server_port'];
    }

    /**
     * @return string
     */
    public function getURI() : string
    {
        return $this->request->server['request_uri'] ?? '';
    }

    /**
     * Gets most possibly client IPv4 Address. This methods searches in
     * $_SERVER["REMOTE_ADDR"] and optionally in $_SERVER["HTTP_X_FORWARDED_FOR"]
     *
     * @return string
     */
    public function getClientAddress(): string
    {
        return $this->getServer('REMOTE_ADDR');
    }

    /**
     * Gets HTTP method which request has been made
     */
    public function getMethod(): string
    {
        $requestMethod = $this->getServer('REQUEST_METHOD');

        if ($requestMethod) {
            return strtoupper($requestMethod);
        }

        return 'GET';
    }

    /**
     * Gets HTTP user agent used to made the request
     */
    public function getUserAgent(): string
    {
        return $this->request->server['http_user_agent'] ?? '';
    }

    /**
     * Checks whether HTTP method is POST. if $_SERVER["REQUEST_METHOD"] === "POST"
     */
    public function isPost(): bool
    {
        return $this->getMethod() === 'POST';
    }

    /**
     * Checks whether HTTP method is GET. if $_SERVER["REQUEST_METHOD"] === "GET"
     */
    public function isGet(): bool
    {
        return $this->getMethod() === 'GET';
    }

    /**
     * Checks whether HTTP method is PUT. if $_SERVER["REQUEST_METHOD"] === "PUT"
     */
    public function isPut(): bool
    {
        return $this->getMethod() === 'PUT';
    }

    /**
     * Checks whether HTTP method is HEAD. if $_SERVER["REQUEST_METHOD"] === "HEAD"
     */
    public function isHead(): bool
    {
        return $this->getMethod() === 'HEAD';
    }

    /**
     * Checks whether HTTP method is DELETE. if $_SERVER["REQUEST_METHOD"] === "DELETE"
     */
    public function isDelete(): bool
    {
        return $this->getMethod() === 'DELETE';
    }

    /**
     * Checks whether HTTP method is OPTIONS. if $_SERVER["REQUEST_METHOD"] === "OPTIONS"
     */
    public function isOptions(): bool
    {
        return $this->getMethod() === 'OPTIONS';
    }

    /**
     * Checks whether HTTP method is PURGE (Squid and Varnish support). if $_SERVER["REQUEST_METHOD"] === "PURGE"
     */
    public function isPurge(): bool
    {
        return $this->getMethod() === 'PURGE';
    }

    /**
     * Checks whether HTTP method is TRACE. if $_SERVER["REQUEST_METHOD"] === "TRACE"
     */
    public function isTrace(): bool
    {
        return $this->getMethod() === 'TRACE';
    }

    /**
     * Checks whether HTTP method is CONNECT. if $_SERVER["REQUEST_METHOD"] === "CONNECT"
     */
    public function isConnect(): bool
    {
        return $this->getMethod() === 'CONNECT';
    }

    /**
     * Checks whether request include attached files
     *
     * @param bool onlySuccessful
     * @return int
     */
    public function hasFiles($onlySuccessful = false) : int
    {
        $numberFiles = 0;
        $files       = $this->request->files;

        if (!is_array($files)) {
            return 0;
        }

        foreach ($files as $file) {
            if (isset($file['error'])) {

                if (!is_array($file['error'])) {
                    if (!$file['error'] || !$onlySuccessful) {
                        $numberFiles++;
                    }
                }

                if (is_array($file['error'])) {
                    $numberFiles += $this->hasFileHelper($file['error'], $onlySuccessful);
                }
            }
        }

        return $numberFiles;
    }

    /**
     * @param $data
     * @param bool $onlySuccessful
     * @return int
     */
    protected final function hasFileHelper($data, bool $onlySuccessful) : int
    {
        $numberFiles = 0;

        if (!is_array($data)) {
            return 1;
        }

        foreach ($data as $value) {
            if (!is_array($value)) {
                if (!$value || !$onlySuccessful) {
                    $numberFiles++;
                }
            }

            if (is_array($value)) {
                $numberFiles += $this->hasFileHelper($value, $onlySuccessful);
            }
        }

        return $numberFiles;
    }

    /**
     * Gets attached files as Phalcon\Http\Request\FileInterface compatible instances
     * @param bool $onlySuccessful
     * @return FileInterface[]
     */
    public function getUploadedFiles(bool $onlySuccessful = false)
    {
        $files      = [];
        $superFiles = $this->request->files;

        if (count($superFiles) > 0) {
            foreach ($superFiles as $prefix => $input) {
                if (is_array($input['name'])) {
                    $smoothInput = $this->smoothFiles(
                        $input['name'],
                        $input['type'],
                        $input['tmp_name'],
                        $input['size'],
                        $input['error'],
                        $prefix
                    );

                    foreach ($smoothInput as $file) {
                        if ($onlySuccessful == false || $file['error'] == UPLOAD_ERR_OK) {
                            $dataFile = [
                                'name' => $file['name'],
                                'type' => $file['type'],
                                'tmp_name' => $file['tmp_name'],
                                'size' => $file['size'],
                                'error' => $file['error']
                            ];

                            $files[] = new File($dataFile, $file['key']);
                        }
                    }
                } else {
                    if ($onlySuccessful == false || $input['error'] == UPLOAD_ERR_OK) {
                        $files[] = new File($input, $prefix);
                    }
                }
            }
        }

        return $files;
    }

    /**
     * @param array $names
     * @param array $types
     * @param array $tmpNames
     * @param array $sizes
     * @param array $errors
     * @param string $prefix
     * @return array
     */
    public function smoothFiles(array $names, array $types, array $tmpNames, array $sizes, array $errors, string $prefix) : array
    {
        $files = [];

        foreach ($names as $idx => $name) {
            $p = $prefix . '.' . $idx;

            if (is_string($name)) {
                $files[] = [
                    "name" => $name,
                    "type" => $types[$idx],
                    "tmp_name" => $tmpNames[$idx],
                    "size" => $sizes[$idx],
                    "error" => $errors[$idx],
                    "key" => $p
                ];
            } elseif (is_array($name)) {
                $parentFiles = $this->smoothFiles(
                    $names[$idx],
                    $types[$idx],
                    $tmpNames[$idx],
                    $sizes[$idx],
                    $errors[$idx],
                    $p
                );

                foreach ($parentFiles as $file) {
                    $files[] = $file;
                }
            }
        }

        return $files;
    }

    /**
     * Gets web page that refers active request. ie: http://www.google.com
     */
    public function getHTTPReferer(): string
    {
        return $this->request->header['http_referer'] ?? '';
    }

    /**
     * Gets content type which request has been made
     */
    public function getContentType()
    {
        $header = $this->request->header;

        if (isset($header['content-type'])) {
            return $header['content-type'];
        } else {
            /**
             * @see https://bugs.php.net/bug.php?id=66606
             */
            if (isset($header['http-content-type'])) {
                return $header['http-content-type'];
            }
        }

        return null;
    }
}
