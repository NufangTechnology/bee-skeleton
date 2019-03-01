<?php
namespace Bee\Http\Request;

/**
 * Interface FileInterface
 *
 * @package Bee\Http\Request
 */
interface FileInterface
{
    /**
     * Returns the file size of the uploaded file
     */
    public function getSize() : int;

	/**
     * Returns the real name of the uploaded file
     */
	public function getName() : string;

	/**
     * Returns the temporal name of the uploaded file
     */
	public function getTempName() : string;

	/**
     * Returns the mime type reported by the browser
     * This mime type is not completely secure, use getRealType() instead
     */
	public function getType() : string;

	/**
     * Gets the real mime type of the upload file using file info
     */
	public function getRealType() : string;

    /**
     * Move the temporary file to a destination
     * @param string $destination
     * @return bool
     */
	public function moveTo(string $destination) : bool;
}
