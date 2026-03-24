<?php

namespace App\Core\Interfaces;

interface ModelsInterface
{
    public static function find
    (
        int $id
    ): ?static;
    public function save(): bool;
    public static function sql
    (
        string $sql,
        string $fetchType = "single",
        array $args = []
    ): mixed ;

    public function where
    (
        string $column,
        mixed $value
    ): static;
    public function get(): array;
}
