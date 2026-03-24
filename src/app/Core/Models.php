<?php

namespace App\Core;

use App\Core\DataBase;
use App\Core\Exceptions\AppException;
use App\Core\Interfaces\ModelsInterface;
use PDO;
use Throwable;

abstract class Models implements ModelsInterface
{
    protected static string $table;
    protected array $fields = [];
    protected array $fillable = [];

    public static function find
    (
        int $id
    ): ?static
    {
        $pdo = DataBase::getConnection();
        $stmt = $pdo->prepare("
            SELECT * FROM ". static::$table . " 
            WHERE id=:id
            "
        );
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
        $allVars = get_object_vars($this);
        $columns = empty($this->fillable) 
            ? array_filter(
                array_keys($allVars),
                fn($key) =>
                !in_array($key,
                    ['fields', 'fillable']
                )
            )
            : $this->fillable;

        $params = [];
        foreach ($columns as $col)
        {
            $params[":$col"] = $this->$col ?? null;
        }

        if (isset($this->id))
        {
            $params[':id'] = $this->id;
            $colSql = array_map(
                fn($col) => 
                    "$col = :$col",
                    array_filter($columns,
                    fn($c) => $c !== 'id')
            );

            $query = "
                UPDATE " . static::$table . " 
                SET " . implode(", ", $colSql) . " 
                WHERE id = :id
                "
            ;
        } else {
            $query = "
                INSERT INTO " . static::$table . " 
                (" . 
                    implode(", ", $columns) . ")
                    VALUES (:" .
                    implode(", :", $columns) . ")
                "
            ;
        }

        $stmt = $pdo->prepare($query);
        $isSave = $stmt->execute($params);
    

        if(!$isSave)
        {
            throw new AppException(
                "Database Save Error: " . implode(
                    ", ", $stmt->errorInfo()
                )
            );
        } 

        if($isSave && !isset($this->id))
        {
            $this->id = (int) $pdo->lastInsertId();
        }

        return $isSave;
    }

    public static function sql(
        string $sql,
        string $fetchType = "single",
        array $args = []
    ) : mixed
    {
        $pdo = DataBase::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($args);
        $data = [];
        if($fetchType === "multi")
        {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    public static function query(): static
    {
        return new static();
    }

    public function where(
        string $column,
        mixed $value
    ): static
    {
        $this->fields[$column] = $value;
        return $this;
    }

    public function get(): array
    {
        $pdo = DataBase::getConnection();
    
        $sql = "SELECT * FROM " . static::$table;
    
        if (!empty($this->fields))
        {
            $conditions = [];
    
            foreach ($this->fields as $key => $value)
            {
                $conditions[] = "$key = :$key";
            }
    
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute($this->fields);
    
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return array_map(fn($row) => static::mapToObject($row), $data);
    }

    public static function transaction(
        callable $callback
    ): mixed
    {
        $pdo = DataBase::getConnection();
    
        $isOuter = false;
    
        try {
            if (!$pdo->inTransaction())
            {
                $pdo->beginTransaction();
                $isOuter = true;
            }
    
            $result = $callback();
    
            if ($isOuter)
            {
                $pdo->commit();
            }
    
            return $result;
    
        } catch (Throwable $e){
            if ($pdo->inTransaction())
            {
                $pdo->rollBack();
            }
    
            throw new AppException(
                "Database Transaction Error: " . $e->getMessage(),
                previous: $e
            );
        }
    }

    public static function transactionBegin(): void
    {
        $pdo = DataBase::getConnection();
    
        if (!$pdo->inTransaction())
        {
            $pdo->beginTransaction();
        }
    }
    
    public static function transactionCommit(): void
    {
        $pdo = DataBase::getConnection();
    
        if ($pdo->inTransaction())
        {
            $pdo->commit();
        }
    }
    
    public static function transactionRollBack(): void
    {
        $pdo = DataBase::getConnection();
    
        if ($pdo->inTransaction())
        {
            $pdo->rollBack();
        }
    }
}
