<?php
namespace Bee\Http\Response;

/**
 * Interface HeadersInterface
 *
 * @package Bee\Http\Response
 */
interface HeadersInterface
{
    /**
     * Sets a header to be sent at the end of the request
     *
     * @param string $name
     * @param string $value
     */
    public function set(string $name, string $value);

    /**
     * Gets a header value from the internal bag
     *
     * @param string $name
     */
    public function get(string $name);

    /**
     * Sets a raw header to be sent at the end of the request
     *
     * @param string $header
     */
	public function setRaw(string $header);

	/**
     * Reset set headers
     */
	public function reset();

    /**
     * Restore a \Bee\Http\Response\Headers object
     *
     * @param array $data
     * @return HeadersInterface
     */
	public static function __set_state(array $data) : HeadersInterface;
}
