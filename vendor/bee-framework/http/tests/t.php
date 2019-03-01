<?php

class Tests
{
    public function lal()
    {
        echo test
    }
}

try {
    (new Tests)->lal();

    call_user_func(function () {
        throw new \Exception('开会呀');
    });
} catch (\Error $e) {
    file_put_contents(__DIR__ . '/error.log', get_class($e));
} catch (\Throwable $e) {
    file_put_contents(__DIR__ . '/exception.log', $e->getMessage());
}