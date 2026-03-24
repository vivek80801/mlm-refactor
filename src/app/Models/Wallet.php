<?php

namespace App\Models;

use App\Core\Models;

class Wallet extends Models
{
    protected static string $table = 'wallet';
    public int $id;
    public int $user_id;
    public float $amount;
    public string $type;
}
