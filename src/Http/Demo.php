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
        return [$_SERVER, $this->global['uniqueId'], $this->global['userId']];
    }
}
