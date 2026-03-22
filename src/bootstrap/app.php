<?php

use App\Core\DataBase;
use App\Core\Exceptions\AppException;
use App\Middleware\AuthMiddleware;

// Middlewares
AuthMiddleware::setName("auth");

// Database Connect
$dbHost = env("DB_HOST");
$dbUser = env("DB_USER");
$dbPort = strlen(env("DB_PORT")) <= 0 
                ? "3600"
                : env("DB_PORT");
$dbPass = env("DB_PASS");
$dbName = env("DB_NAME");

if(strlen($dbHost) <= 0)
{
    throw new AppException("Database: Host can not be empty");
}

if(strlen($dbUser) <= 0)
{
    throw new AppException("Database: User can not be empty");
}

if(strlen($dbName) <= 0)
{
    throw new AppException("Database: Name can not be empty");
}

DataBase::connect(
    "mysql:host={$dbHost};port={$dbPort};dbname={$dbName}",
    $dbUser,
    $dbPass
);

require_once basePath() . "src/web/Route.php";

