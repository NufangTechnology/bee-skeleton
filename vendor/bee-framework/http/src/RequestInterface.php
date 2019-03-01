<?php
namespace Bee\Http;

use Bee\Http\Request\FileInterface;

/**
 * Interface RequestInterface
 *
 * @package Bee\Http
 */
interface RequestInterface
{
    /**
     * @param string|null $name
     * @param null $filters
     * @param null $defaultValue
     * @return mixed
     */
    public function getQuery(string $name = null, $filters = null, $defaultValue = null);

    /**
     * @param string|null $name
     * @param null $filters
     * @param null $defaultValue
     * @return mixed
     */
    public function getPost(string $name = null, $filters = null, $defaultValue = null);

    /**
     * @param string $header
     * @return string
     */
    public function getHeader(string $header = '');

    /**
     * @param string $name
     * @return mixed
     */
    public function getServer(string $name = '');

    /**
     * Gets HTTP schema (http/https)
     */
    public function getScheme() : string;

    /**
     * Checks whether request has been made using ajax. Checks if $_SERVER["HTTP_X_REQUESTED_WITH"] === "XMLHttpRequest"
     */
    public function isAjax() : bool;

    /**
     * Checks whether request has been made using any secure layer
     */
    public function isSecureRequest() : bool;

    /**
     * Gets HTTP raw request body
     */
    public function getRawBody() : string;

    /**
     * Gets active server address IP
     */
    public function getServerAddress() : string;

    /**
     * Gets active server name
     */
    public function getServerName() : string;

    /**
     * Gets host name used by the request
     */
    public function getHttpHost() : string;

    /**
     * Gets information about the port on which the request is made
     */
    public function getPort() : int;

    /**
     * Gets most possibly client IPv4 Address. This methods searches in
     * $_SERVER["REMOTE_ADDR"] and optionally in $_SERVER["HTTP_X_FORWARDED_FOR"]
     *
     * @return string
     */
    public function getClientAddress() : string;

    /**
     * Gets HTTP method which request has been made
     */
    public function getMethod() : string;

    /**
     * Gets HTTP user agent used to made the request
     */
    public function getUserAgent() : string;

    /**
     * Checks whether HTTP method is POST. if $_SERVER["REQUEST_METHOD"] === "POST"
     */
    public function isPost() : bool;

    /**
     * Checks whether HTTP method is GET. if $_SERVER["REQUEST_METHOD"] === "GET"
     */
    public function isGet() : bool;

    /**
     * Checks whether HTTP method is PUT. if $_SERVER["REQUEST_METHOD"] === "PUT"
     */
    public function isPut() : bool;

    /**
     * Checks whether HTTP method is HEAD. if $_SERVER["REQUEST_METHOD"] === "HEAD"
     */
    public function isHead() : bool;

    /**
     * Checks whether HTTP method is DELETE. if $_SERVER["REQUEST_METHOD"] === "DELETE"
     */
    public function isDelete() : bool;

    /**
     * Checks whether HTTP method is OPTIONS. if $_SERVER["REQUEST_METHOD"] === "OPTIONS"
     */
    public function isOptions() : bool;

    /**
     * Checks whether HTTP method is PURGE (Squid and Varnish support). if $_SERVER["REQUEST_METHOD"] === "PURGE"
     */
    public function isPurge() : bool;

    /**
     * Checks whether HTTP method is TRACE. if $_SERVER["REQUEST_METHOD"] === "TRACE"
     */
    public function isTrace() : bool;

    /**
     * Checks whether HTTP method is CONNECT. if $_SERVER["REQUEST_METHOD"] === "CONNECT"
     */
    public function isConnect() : bool;

    /**
     * Checks whether request include attached files
     *
     * @param bool onlySuccessful
     * @return bool
     */
    public function hasFiles($onlySuccessful = false);

    /**
     * Gets attached files as Phalcon\Http\Request\FileInterface compatible instances
     * @param bool $onlySuccessful
     * @return FileInterface[]
     */
    public function getUploadedFiles(bool $onlySuccessful = false);

    /**
     * Gets web page that refers active request. ie: http://www.google.com
     */
    public function getHTTPReferer() : string;
}
