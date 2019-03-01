<?php
namespace Bee\Http;

use Bee\Http\Response\Headers;
use Bee\Http\Response\HeadersInterface;

/**
 * Response
 *
 * @package Ant\Http
 */
class Response implements ResponseInterface
{
    /**
     * @var \Swoole\Http\Response
     */
    protected $response;

    /**
     * @var Headers
     */
    protected $headers;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var bool
     */
    protected $sent = false;

    /**
     * 数据源
     *
     * @param \Swoole\Http\Response $response
     */
    public function withSource(\Swoole\Http\Response $response)
    {
        $this->response = $response;
        $this->headers  = new Headers();

        // 更新发送状态
        $this->sent     = false;
    }

    /**
     * Sets the HTTP response code
     *
     * <code>
     *     $response->setStatusCode(404, "Not Found");
     * </code>
     *
     * @param int $code
     * @param string|null $message
     * @return Response
     */
    public function setStatusCode(int $code, string $message = null): ResponseInterface
    {
        $this->response->status($code);

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStatusCode()
    {
        $statusCode = substr($this->getHeaders()->get('Status'), 0, 3);
        
        return $statusCode ? (int)$statusCode : null;
    }

    /**
     * Returns headers set by the user
     */
    public function getHeaders(): HeadersInterface
    {
        return $this->headers;
    }

    /**
     * Overwrites a header in the response
     *
     * @param string $name
     * @param $value
     * @return Response
     */
    public function setHeader(string $name, $value): ResponseInterface
    {
        $this->headers->set($name, $value);
        
        return $this;
    }

    /**
     * Send a raw header to the response
     *
     * @param string $header
     * @return Response
     */
    public function setRawHeader(string $header): ResponseInterface
    {
        $this->headers->setRaw($header);
        
        return $this;
    }

    /**
     * Resets all the established headers
     */
    public function resetHeaders(): ResponseInterface
    {
        $this->headers->reset();
        
        return $this;
    }

    /**
     * Sets output expire time header
     *
     * @param \DateTime $datetime
     * @return Response
     */
    public function setExpires(\DateTime $datetime): ResponseInterface
    {
        $date = clone $datetime;
        /**
         * All the expiration times are sent in UTC
         * Change the timezone to utc
         */
        $date->setTimezone(new \DateTimeZone('UTC'));

        /**
         * The 'Expires' header set this info
         */
        $this->setHeader('Expires', $date->format('D, d M Y H:i:s') . ' GMT');
        
        return $this;
    }
    
    public function setLastModified(\DateTime $datetime) : ResponseInterface
    {
        $date = clone $datetime;

        /**
         * All the expiration times are sent in UTC
         * Change the timezone to utc
         */
        $date->setTimezone(new \DateTimeZone('UTC'));

        /**
         * The 'Last-Modified' header sets this info
         */
        $this->setHeader("Last-Modified", $date->format("D, d M Y H:i:s") . " GMT");

        return $this;
    }

    /**
     * @param int $minutes
     * @return Response
     * @throws \Exception
     */
    public function setCache(int $minutes) : ResponseInterface
    {
        $date = new \DateTime();
        $date->modify('+' . $minutes . ' minutes');

        $this->setExpires($date);
        $this->setHeader('Cache-Control', 'max-age=' . ($minutes * 60));

        return $this;
    }

    /**
     * Sends a Not-Modified response
     */
    public function setNotModified(): ResponseInterface
    {
        $this->setStatusCode(304, "Not modified");

        return $this;
    }

    /**
     * Sets the response content-type mime, optionally the charset
     *
     * @param string $contentType
     * @param string $charset
     * @return Response
     */
    public function setContentType(string $contentType, $charset = null): ResponseInterface
    {
        if ($charset === null) {
            $this->setHeader('Content-Type', $contentType);
        } else {
            $this->setHeader('Content-Type', $contentType . '; charset=' . $charset);
        }

        return $this;
    }

    /**
     * Sets the response content-length
     *
     * @param int $contentLength
     * @return Response
     */
    public function setContentLength(int $contentLength): ResponseInterface
    {
        $this->setHeader("Content-Length", $contentLength);

		return $this;
    }

    /**
     * Set a custom ETag
     *
     *<code>
     * $response->setEtag(md5(time()));
     *</code>
     *
     * @param string $etag
     * @return Response
     */
    public function setEtag(string $etag) : ResponseInterface
	{
		$this->setHeader("Etag", $etag);

		return $this;
	}

    /**
     * Redirect by HTTP to another URL
     *
     *<code>
     * // Using a string redirect (internal/external)
     * $response->redirect("http://en.wikipedia.org", true);
     * $response->redirect("http://www.example.com/new-location", true, 301);
     *
     * @param null $location
     * @param bool $externalRedirect
     * @param int $statusCode
     * @return Response
     */
    public function redirect($location = null, bool $externalRedirect = false, int $statusCode = 302): ResponseInterface
    {
        if (!$location) {
            $location = '';
        }

        /**
         * The HTTP status is 302 by default, a temporary redirection
         */
        if ($statusCode < 300 || $statusCode > 308) {
            $statusCode = 302;
        }

        /**
         * Change the current location using 'Location'
         */
        $this->response->redirect($location, $statusCode);

        return $this;
    }

    /**
     * Sets HTTP response body
     *
     * @param string $content
     * @return Response
     */
    public function setContent(string $content): ResponseInterface
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Sets HTTP response body. The parameter is automatically converted to JSON
     *
     *<code>
     * $response:setJsonContent(
     *     [
     *         "status" => "OK",
     *     ]
     * );
     *</code>
     *
     * @param $content
     * @param int $jsonOptions
     * @param int $depth
     * @return Response
     */
    public function setJsonContent($content, int $jsonOptions = 0, int $depth = 512): ResponseInterface
    {
        $this->setContentType('application/json', 'UTF-8');
        $this->setContent(json_encode($content, $jsonOptions, $depth));

        return $this;
    }

    /**
     * Appends a string $to the HTTP response body
     *
     * @param $content
     * @return Response
     */
    public function appendContent($content): ResponseInterface
    {
        $this->content = $this->getContent() . $content;

        return $this;
    }

    /**
     * Gets the HTTP response body
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return bool
     */
    public function isSent() : bool
    {
        return $this->sent;
    }

    /**
     * Sends headers to the client
     */
    public function sendHeaders(): ResponseInterface
    {
        $headers = $this->headers->toArray();

        // 发送 header
        foreach ($headers as $key => $value) {
            $this->response->header($key, $value);
        }

        return $this;
    }

    /**
     * Prints out HTTP response to the client
     *
     * @return ResponseInterface|false
     */
    public function send(): ResponseInterface
    {
        if ($this->sent) {
            trigger_error('Response was already sent', E_USER_WARNING);
            return $this;
        }

        $this->sendHeaders();

        /**
         * Output the response body
         */
        if ($this->content) {
            $this->response->write($this->content);
        } else {
            if ($this->file) {
                $this->response->sendfile($this->file);
            }
        }

        $this->sent = true;

        $this->response->end();

        return $this;
    }

    /**
     * Sets an attached file to be sent at the end of the request
     *
     * @param string $filePath
     * @param mixed $attachmentName
     * @param mixed $attachment
     * @return Response
     */
    public function setFileToSend(string $filePath, $attachmentName = null, $attachment = null) : ResponseInterface
    {
        if (is_string($attachmentName)) {
            $basePath = basename($filePath);
        } else {
            $basePath = $attachmentName;
        }

        if ($attachment) {
            $this->setRawHeader('Content-Description: File Transfer');
			$this->setRawHeader('Content-Type: application/octet-stream');
			$this->setRawHeader('Content-Disposition: attachment; filename=' . $basePath);
			$this->setRawHeader('Content-Transfer-Encoding: binary');
        }

        $this->file = $filePath;

        return $this;
    }

    /**
     * Remove a header in the response
     *
     *<code>
     * $response->removeHeader("Expires");
     *</code>
     *
     * @param string $name
     * @return Response
     */
    public function removeHeader(string $name) : ResponseInterface
    {
        $this->headers->remove($name);

        return $this;
    }
}
