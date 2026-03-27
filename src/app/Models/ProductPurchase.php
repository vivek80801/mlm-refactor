<?php

namespace App\Models;

use App\Core\Models;

class  ProductPurchase extends Models
{
    protected static string $table = 'productPurchases';

    public int $id;
    public int $user_id;
    public int $product_id;
}
