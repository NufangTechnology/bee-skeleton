<?php
use Bee\Router\Collection;

return call_user_func(function () {
    $rules = [];

    $rules['demo'] = new Collection(\Star\Http\Demo::class, '/');
    $rules['demo']
        ->get('/', 'word')
        ->post('/', 'upload')
    ;

    return $rules;
});
