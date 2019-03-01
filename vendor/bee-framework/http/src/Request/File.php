<?php
namespace Bee\Http\Request;

/**
 * 上传的文件项
 *
 * @package Bee\Http\Request
 */
class File implements FileInterface
{
    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $tmp;

    /**
     * @var string|null
     */
    protected $size;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $realType;

    /**
     * @var string|null
     */
    protected $error;

    /**
     * @var string|null
     */
    protected $key;

    /**
     * @var string|null
     */
    protected $ext;

    /**
     * File
     *
     * @param array $file
     * @param null $key
     */
    public function __construct(array $file, $key = null)
    {
        if (isset($file['name'])) {
            $this->name = $file['name'];

            if (defined('PATHINFO_EXTENSION')) {
                $this->ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            }
        }

        if (isset($file['tmp_name'])) {
            $this->tmp = $file['tmp_name'];
        }

        if (isset($file['size'])) {
            $this->size = $file['size'];
        }

        if (isset($file['type'])) {
            $this->type = $file['type'];
        }

        if (isset($file['error'])) {
            $this->error = $file['error'];
        }

        if ($key) {
            $this->key = $key;
        }
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * Returns the file size of the uploaded file
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Returns the real name of the uploaded file
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the temporal name of the uploaded file
     */
    public function getTempName(): string
    {
        return $this->tmp;
    }

    /**
     * Returns the mime type reported by the browser
     * This mime type is not completely secure, use getRealType() instead
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Gets the real mime type of the upload file using file info
     */
    public function getRealType(): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if (!is_resource($finfo)) {
            return '';
        }

        $mine = finfo_file($finfo, $this->tmp);
        finfo_close($finfo);

        return $mine;
    }

    /**
     * Checks whether the file has been uploaded via Post.
     *
     * @return bool
     */
    public function isUploadedFile() : bool
    {
        $tmp = $this->getTempName();
        return is_string($tmp) && is_uploaded_file($tmp);
    }

    /**
     * Move the temporary file to a destination
     * @param string $destination
     * @return bool
     */
    public function moveTo(string $destination): bool
    {
        return move_uploaded_file($this->tmp, $destination);
    }
}
