<?php

namespace App\Core\Exceptions;

use App\Core\Exceptions\AppException;
use Throwable;

class FileNotFoundException extends AppException
{
   public function __construct(
       string $message = "",
       int $code = 0,
       Throwable|null $previous = null
   )
   {
       $message = "File Error: {$message} not found";
       parent::__construct($message, $code, $previous);
   }
}
