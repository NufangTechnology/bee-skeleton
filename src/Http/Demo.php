<?php
namespace Star\Http;

use Star\Util\Http;

/**
 * HTTP示例
 *
 * @package Star\Http
 */
class Demo extends Http
{
    /**
     * @return array
     */
    public function hello()
    {
        return ['hello word!'];
    }
}
