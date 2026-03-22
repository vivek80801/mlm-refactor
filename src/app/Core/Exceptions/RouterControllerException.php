<?php

namespace App\Core\Exceptions;

use App\Core\Exceptions\RouterException;
use Throwable;

class RouterControllerException extends RouterException
{
    public function __construct(
        string $message = "",
        int $code = 0,
        Throwable|null $previous = null
    )
    {
        $message = "Controller Error: {$message} not found";
        return parent::__construct($message, $code, $previous);
    }
}
