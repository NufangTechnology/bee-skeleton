<?php
namespace Bee\Http\Response;

/**
 * Class Headers
 *
 * @package Bee\Http\Response
 */
class Headers implements HeadersInterface
{
    /**
     * @var array
     */
    protected $headers = [
        'Content-Type' => 'application/json; charset=utf-8'
    ];

    /**
     * Sets a header to be sent at the end of the request
     *
     * @param string $name
     * @param string $value
     */
    public function set(string $name, string $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * Gets a header value from the internal bag
     *
     * @param string $name
     * @return bool|mixed
     */
    public function get(string $name)
    {
        if (isset($this->headers[$name])) {
            return $this->headers[$name];
        }

        return false;
    }

    /**
     * Sets a raw header to be sent at the end of the request
     *
     * @param string $header
     */
    public function setRaw(string $header)
    {
        $this->headers[$header] = null;
    }

    /**
     * Removes a header to be sent at the end of the request
     *
     * @param string $name
     */
    public function remove(string $name)
    {
        unset($this->headers[$name]);
    }

    /**
     * Reset set headers
     */
    public function reset()
    {
        $this->headers = [];
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return $this->headers;
    }

    /**
     * Restore a \Bee\Http\Response\Headers object
     *
     * @param array $data
     * @return HeadersInterface
     */
    public static function __set_state(array $data): HeadersInterface
    {
        $headers = new self();

        if (isset($data['headers'])) {
            $dataHeaders = $data['headers'];

            foreach ($dataHeaders as $key => $value) {
                $headers->set($key, $value);
            }
        }

        return $headers;
    }
}