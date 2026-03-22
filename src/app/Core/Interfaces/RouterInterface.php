<?php

namespace App\Core\Interfaces;

interface RouterInterface
{
    public function get
    (
        string $url,
        array $controller,
    ): RouterInterface;
    public function post
    (
        string $url,
        array $controller,
    ): RouterInterface;
    public function middleware
    (
        string $middleware
    ): RouterInterface;
    public function resolve
    (
        string $url,
        string $method,
    ): void;

}
