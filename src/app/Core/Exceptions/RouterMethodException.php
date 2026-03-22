<?php

namespace App\Core\Exceptions;

use App\Core\Exceptions\RouterException;
use Throwable;

class RouterMethodException extends RouterException
{
    public function __construct(
        string $message = "",
        int $code = 0,
        Throwable|null $previous = null
    )
    {
        $message = "Controller Method Error: {$message} not found.";
        return parent::__construct($message, $code, $previous);
    }
}
