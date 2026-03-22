<?php

namespace App\Core\Interfaces;

interface RequestInterface
{
       public function getUri(): string;
    public function getMethod(): string;
    public function input
    (
        string $key,
        $default = null
    ): string | null;
    public function all(): array;

}
