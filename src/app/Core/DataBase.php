<?php

namespace App\Core;

use App\Core\Exceptions\DatabaseException;
use App\Core\Interfaces\DataBaseInterface;
use PDO;

class DataBase implements DataBaseInterface
{
    private static PDO|NULL $connection = null;

    public static function connect
    (
        string $dsn,
        string $username,
        string $password,
    ):void
    {
        if(self::$connection === null)
        {
            self::$connection = new PDO(
                $dsn,
                $username,
                $password
            );
            self::$connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        }
    }

    public static function getConnection(): PDO
    {
        if(self::$connection === null)
        {
            throw new DatabaseException(env('DB_NAME'));
        }

        return self::$connection;
    }
}
