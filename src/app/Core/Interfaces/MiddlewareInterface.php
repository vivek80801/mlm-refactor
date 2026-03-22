<?php

namespace App\Core\Interfaces;

interface MiddlewareInterface
{
    public static function setName
    (
        string $name
    ):void;
}
