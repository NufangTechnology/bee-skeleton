<?php

function PR($data)
{
    file_put_contents(RUNTIME_PATH . '/print_r.log', var_export($data, true) . PHP_EOL . PHP_EOL, 8);
}