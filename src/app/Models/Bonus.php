<?php

namespace App\Models;

use App\Core\Models;

class  Bonus extends Models
{
    protected static string $table = 'bonus';

    protected array $fillable = [
        "bonus_type",
        "amount",
        "user_id"
    ];

    public int $id;
    public string $bonus_type;
    public int $amount;
    public int $user_id;
    public string $code;
    public int $product_id;
}
