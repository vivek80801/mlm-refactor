<?php

namespace App\Core;

use App\Core\Interfaces\ErrorHandlerInterface;
use DateTime;
use ErrorException;
use Throwable;

class ErrorHandler implements ErrorHandlerInterface
{
    public function index
    (
        int $errno,
        string $errstr,
        string $errfile,
        int $errline,
    ): bool
    {
        $errorMessage = "Warning: : {$errno} - {$errfile}:{$errline} => {$errstr}" . PHP_EOL;

        $this->log($errorMessage);
        return true;
    }
    public function handleException(Throwable $e): void
    {
        $errorMessage = "Fetal Error: {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}" ;

        $this->log($errorMessage);
        $this->render($errorMessage);
    }

    public function handleShutdown(): void
    {
        $error = error_get_last();
    
        if ($error === null) return;

        $fatalErrors = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR];
    
        if (!in_array($error['type'], $fatalErrors)) return;
            $exception = new ErrorException(
                $error['message'],
                0,
                $error['type'],
                $error['file'],
                $error['line']
            );
    
        $this->handleException($exception);
    }

    public function log(string $errorMessage): void
    {
        // Setting up Indian Time Zone
        date_default_timezone_set('Asia/Kolkata');
        $date = new DateTime();
        $formatedDate = $date->format("d-m-Y");
        $timeStamp = $date->format("d-m-Y h:i:s");

        $errDir = basePath() . "logs/";
    
        if (!is_dir($errDir)) {
            mkdir($errDir, 0777, true);
        }

        $errFile = $errDir . "error_". $formatedDate . ".log";
        $newErrorMessage = $timeStamp . ": " . $errorMessage . PHP_EOL;
    
        file_put_contents($errFile, $newErrorMessage, FILE_APPEND);
    }

    public function render(string $message, int $code = 500): void
    {
        if (env('APP_DEBUG'))
        {
            $this->renderHtml($message);
        } else {
            http_response_code($code);
            view("errors/servererror");
        }
    }

    private function renderHtml(string $message): void
    {
        view("errors/devservererror", [
            "message" => $message
        ]);
    }
}
