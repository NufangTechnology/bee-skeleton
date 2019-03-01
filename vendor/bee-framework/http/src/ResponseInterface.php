<?php
namespace Bee\Http;

use Bee\Http\Response\HeadersInterface;

/**
 * Interface ResponseInterface
 *
 * @package Bee\Http
 */
interface ResponseInterface
{
    /**
     * Sets the HTTP response code
     *
     * @param int $code
     * @param string|null $message
     * @return ResponseInterface
     */
    public function setStatusCode(int $code, string $message = null) : ResponseInterface;

	/**
     * Returns headers set by the user
     */
	public function getHeaders() : HeadersInterface;

    /**
     * Overwrites a header in the response
     *
     * @param string $name
     * @param $value
     * @return ResponseInterface
     */
	public function setHeader(string $name, $value) : ResponseInterface;

    /**
     * Send a raw header to the response
     *
     * @param string $header
     * @return ResponseInterface
     */
	public function setRawHeader(string $header) : ResponseInterface;

	/**
     * Resets all the established headers
     */
	public function resetHeaders() : ResponseInterface;

    /**
     * Sets output expire time header
     *
     * @param \DateTime $datetime
     * @return ResponseInterface
     */
	public function setExpires(\DateTime $datetime) : ResponseInterface;

	/**
     * Sends a Not-Modified response
     */
	public function setNotModified() : ResponseInterface;

    /**
     * Sets the response content-type mime, optionally the charset
     *
     * @param string $contentType
     * @param string $charset
     * @return ResponseInterface
     */
	public function setContentType(string $contentType, $charset = null) : ResponseInterface;

    /**
     * Sets the response content-length
     *
     * @param int $contentLength
     * @return ResponseInterface
     */
	public function setContentLength(int $contentLength) : ResponseInterface;

    /**
     * Redirect by HTTP to another action or URL
     *
     * @param null $location
     * @param bool $externalRedirect
     * @param int $statusCode
     * @return ResponseInterface
     */
	public function redirect($location = null, bool $externalRedirect = false, int $statusCode = 302) : ResponseInterface;

    /**
     * Sets HTTP response body
     *
     * @param string $content
     * @return ResponseInterface
     */
	public function setContent(string $content) : ResponseInterface;

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
     * @return ResponseInterface
     */
	public function setJsonContent($content) : ResponseInterface;

    /**
     * Appends a string $to the HTTP response body
     *
     * @param $content
     * @return ResponseInterface
     */
	public function appendContent($content) : ResponseInterface;

	/**
     * Gets the HTTP response body
     */
	public function getContent() : string;

	/**
     * Sends headers to the client
     */
	public function sendHeaders() : ResponseInterface;

	/**
     * Prints out HTTP response to the client
     */
	public function send() : ResponseInterface;

    /**
     * Sets an attached file to be sent at the end of the request
     *
     * @param string $filePath
     * @param null $attachmentName
     * @return ResponseInterface
     */
	public function setFileToSend(string $filePath, $attachmentName = null) : ResponseInterface;
}
