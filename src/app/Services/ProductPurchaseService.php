<?php

namespace App\Services;

use App\Models\ProductPurchase;

class  ProductPurchaseService
{
    public function createProductPurchase
    (
        int $productId,
        int $userId,
    )
    {
        $productPurchaseInsert = new ProductPurchase();
        $productPurchaseInsert->product_id = $productId;
        $productPurchaseInsert->user_id = $userId;
        $productPurchaseInsert->save();
    }
}
