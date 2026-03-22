<?php

namespace App\Core\Interfaces;

use Throwable;

interface ErrorHandlerInterface
{
    public function index
    (
        int $errno,
        string $errstr,
        string $errfile,
        int $errline,
    ): bool;
    public function handleException(Throwable $e): void;
    public function handleShutdown(): void;
    public function log(string $errorMessage): void;
    public function render(string $message, int $code = 500): void;

}
