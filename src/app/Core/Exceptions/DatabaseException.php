<?php

namespace App\Core\Exceptions;

use Exception;
use Throwable;

class DatabaseException extends Exception
{
    public function __construct(
        string $message = "",
        int $code = 0,
        Throwable|null $previous = null
    )
    {
       $message = "Database Error: {$message} not found. can not connect to database";
        return parent::__construct($message, $code, $previous);
    }
}
