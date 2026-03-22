<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{

    public function all(): array
    {
        return Product::sql("SELECT * FROM product", "multi");
    }

    /**
    * @return array<string, mixed>
    */
    public function productDetail
    (
        int $productId,
        int $userId
    ): array
    {
        $product = Product::sql("SELECT * FROM product WHERE id=:id",
            "single",
            [
                ":id" => $productId
            ]
        );

        $purchasedProduct = Product::sql("SELECT count(*)
            AS product_count FROM product_purchases WHERE
            user_id=:user_id and product_id=:product_id",
            "single",
            [
            ":user_id" => $userId,
            ":product_id" => $productId,
        ]);
        return [
            "product" => $product,
            "is_hero_purchased_product" =>
            $purchasedProduct["product_count"] > 0 
            ? false : true,
        ];
    }

}
