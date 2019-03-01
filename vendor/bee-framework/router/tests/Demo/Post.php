<?php
namespace Bee\Router\Tests\Demo;

class Post
{
    public function root()
    {
        $a = func_get_args();

        return __CLASS__ . '/' . __DIR__;
    }

    public function hello()
    {
        return __CLASS__ . '/' . __METHOD__;
    }
}