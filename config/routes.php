<?php
use Phalcon\Mvc\Micro\Collection;

return call_user_func(function () {
    $rules = [];

    $rules['demo'] = new Collection;
    $rules['demo']
        ->setHandler(\Star\Http\Demo::class)
        ->setPrefix('/')
        ->setLazy(true)
        ->get('/', 'hello')
    ;

    return $rules;
});