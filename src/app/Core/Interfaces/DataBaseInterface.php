<?php

namespace App\Core\Interfaces;

use PDO;

interface DataBaseInterface
{
    public static function connect
    (
        string $dsn,
        string $username,
        string $password,
    ): void;
    public static function getConnection(): PDO;

}
