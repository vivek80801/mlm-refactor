<?php

namespace App\Models;

use App\Core\Models;

class  Product extends Models
{
    protected static string $table = 'products';

    public int $id;
    public string $product_name;
    public string $image;
    public int $cycle;
    public float $daily_income;
    public float $total_income;
    public float $price;
    public string $status;
    public float $referal_bonus;
}
