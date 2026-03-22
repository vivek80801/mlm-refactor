<?php

use App\Core\ErrorHandler;
use App\Core\Exceptions\FileNotFoundException;

$base_path = explode("public", __DIR__)[0];

require_once $base_path . "src/app/Core/helper.php";

loadEnv();

spl_autoload_register(function($class) use ($base_path) {
    $class_path = $base_path . "src/" . lcfirst(str_replace("\\", "/", $class)) . ".php";
    if(file_exists($class_path))
    {
        require_once $class_path;
    } else {
        throw new FileNotFoundException($class_path);
    }
});

error_reporting(E_ALL);
ini_set("display_errors", 1);

$errorHandler = new ErrorHandler();

set_error_handler([$errorHandler, "index"], E_ALL);
set_exception_handler([$errorHandler, 'handleException']);
register_shutdown_function([$errorHandler, 'handleShutdown']);

require_once $base_path . "src/bootstrap/app.php";
