<?php
require dirname(__DIR__) . '/vendor/autoload.php';

$rules = [];

$rules['demo_1'] = new \Bee\Router\Collection(\Bee\Router\Tests\Demo\Get::class, '');
$rules['demo_1']
    ->get('/', 'root')
    ->get('/hello', 'hello')
    ->get('line', 'line')
;

$rules['demo_2'] = new \Bee\Router\Collection(\Bee\Router\Tests\Demo\Post::class, '/post');
$rules['demo_2']
    ->post('/', 'root')
    ->post('/hello', 'hello')
;

$router = new \Bee\Router\Router();

foreach ($rules as $rule) {
    $router->mount($rule);
}

$handler = $router->match('GET', '/');

$value = $handler->callMethod([]);

$handler = $router->match('POST', '/');

$value = $handler->callMethod([]);


echo $value;
