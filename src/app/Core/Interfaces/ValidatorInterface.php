<?php

namespace App\Core\Interfaces;

interface ValidatorInterface
{
    public static function validate
    (
        array $args,
        Object $request
    ): array;
}
