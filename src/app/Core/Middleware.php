<?php

namespace App\Core;

use App\Core\Interfaces\MiddlewareInterface;

class Middleware implements MiddlewareInterface
{
    public static $middlewares = [];
    // This is a hack for pausing function.
    // I don't  know. How to implement next function calls
    // TODO: Remove isContine without breaking functionality
    // TODO: implement next function
    public static $isContinue = true;
    public static function setName
    (
        string $name
    ): void
    {
        $middleware = [
            "name" => $name,
            "class" => static::class,
        ];

        array_push(static::$middlewares, $middleware);
    }
}
