<?php

register_shutdown_function(function () {
    file_put_contents(__DIR__ . '/shutdown.log', json_encode(func_get_args()) . PHP_EOL, 8);
});

set_error_handler(function () {
    file_put_contents(__DIR__ . '/error.log', json_encode(func_get_args()) . PHP_EOL, 8);
});

set_exception_handler(function (\Throwable $e) {
    file_put_contents(__DIR__ . '/exception.log', get_class($e) . PHP_EOL, 8);
});

require 't.php';