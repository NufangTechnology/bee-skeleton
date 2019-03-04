<?php
namespace Star\Http;

use Star\Util\Http;

/**
 * 手机网页错误信息
 *
 * @package Star\Http
 */
class Demo extends Http
{
    public function say()
    {
        throw new \Exception('hello word');

        return 'hello word';
    }

    public function upload()
    {
        return 'upload';
    }
}
