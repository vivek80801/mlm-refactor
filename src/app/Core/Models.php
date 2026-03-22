<?php

namespace App\Core;

use App\Core\DataBase;
use App\Core\Interfaces\ModelsInterface;
use PDO;

abstract class Models implements ModelsInterface
{
    protected static string $table;

    public static function find
    (
        int $id
    ): ?static
    {
        $pdo = DataBase::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM ". static::$table . " WHERE id=:id");
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? static::mapToObject($data) : null;
    }

    private static function mapToObject
    (
        array $data,
    ): static
    {
        $object = new static();

        foreach($data as $key => $value)
        {
            $object->$key = $value;
        }

        return $object;
    }

    public function save(): bool
    {
        $pdo = DataBase::getConnection();
        $fields = get_object_vars($this);
        $columns = array_keys($fields);

        if(isset($this->id))
        {
            $query = "UPDATE " . static::$table .
                " SET " . 
                implode(
                    ", ",
                    array_map(
                        fn($col)
                        => 
                        "$col = :$col", $columns
                    )
                ) . " WHERE id = :id";
        } else {
            $query = "INSERT INTO " . static::$table . 
                " (" . implode(
                    ", ",
                    $columns
                ) . ") VALUES (" . implode(
                    ", ",
                    array_map(
                        fn($col) 
                        => ":$col", $columns
                    )
                ) . ")";
        }

        $stmt = $pdo->prepare($query);
        return $stmt->execute($fields);
    }

    public static function sql(
        string $sql,
        string $fetchType = "single",
        array $args = []
    ): array
    {
        $pdo = DataBase::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($args);
        if($fetchType === "multi")
        {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
}
