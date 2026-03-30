<?php

namespace App\Services;

use App\Core\Exceptions\AppException;
use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\User;
use App\Models\Wallet;
use App\Services\UserWalletService;
use App\Services\ProductPurchaseService;
use App\Services\BonusService;
use Throwable;

class ProductService
{

    public function __construct
    (
        private UserWalletService $userWalletService,
        private ProductPurchaseService $productPurchaseService,
        private BonusService $bonusService
    )
    {}
    /*
    *@return array<int, Product>
    */
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
        $product = Product::query()
            ->where("id", $productId)
            ->get()[0]
        ;

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
    public function productRental
    (
        int $productId,
        int $userId,
        string|null $inviteCode
    ): void
    {
        if(!$inviteCode)
        {
            throw new AppException(
                "Product Rental Error: User does not have invite code"
            );
        }

        try {
            $userWallet = Wallet::query()
                ->where("user_id", $userId)
                ->get()[0]
            ;

            $product = Product::query()
                ->where("id", $productId)
                ->get()[0]
            ;


            if(
                (int) $userWallet->amount > 0 &&
                (int) $userWallet->amount >= $product->price
            )
            {
                ProductPurchase::transaction(
                    function () use (
                        $userId,
                        $inviteCode,
                        $userWallet,
                        $productId,
                        $product
                    )
                    {
                        $welcomeBonusAmount = 0;
                        $userReferedBy = User::query()
                            ->where("referral_code", $inviteCode)
                            ->get()[0]
                        ;

                        $referedByUserWallet = Wallet::query()
                            ->where("user_id", (int) $userReferedBy->id)
                            ->get()[0]
                        ;

                        $purchaseProduct = ProductPurchase::query()
                                            ->where("user_id", $userId)->get();

                        $isProductPurchasedBefore = empty($purchaseProduct)
                                                ? false : true;


                        if(!$isProductPurchasedBefore)
                        {
                            $welcomeBonus = User::sql(
                                "SELECT id, amount FROM welcome_bonus_by_admin"
                            );

                            $welcomeBonusAmount =
                                (float) $welcomeBonus["amount"];

                            $this
                                ->bonusService
                                ->createBonus(
                                "welcome",
                                $userId,
                                (float)$welcomeBonus["amount"]
                            );

                        }

                        $newAmount =
                            ((float) $userWallet->amount -
                            (float) $product->price ) + 
                            (float) $welcomeBonusAmount;


                        $this
                            ->userWalletService
                            ->updateWallet(
                                $userWallet->id,
                                $userId,
                                $newAmount
                            );

                        $referedUserNewWalletAmount = 
                            (float) $referedByUserWallet->amount +
                            (float) $product->referal_bonus;

                        $this
                            ->userWalletService
                            ->updateWallet(
                            $referedByUserWallet->id,
                            $userReferedBy->id,
                            $referedUserNewWalletAmount
                        );

                        $this
                            ->productPurchaseService
                            ->createProductPurchase(
                                $productId,
                                $userId
                            );

                        $commissions = User::sql("SELECT * FROM commission_for_groups",
                            "multi"
                        );

                        $this->assignCommissionsToGroups(
                            (int) $userReferedBy->id,
                            $commissions,
                            $product
                        );
                });
            }else {
                throw new AppException("Error: Insificient Balance");
            }
        }catch(Throwable $e){
            throw new AppException("Error: " . $e->getLine());
        }
    }

    private function assignCommissionsToGroups
    (
        int $referredById,
        array $commissions,
        Product $product,
        int $count = 0
    ):void
    {
        $group = User::sql(
            "SELECT
            r.*, u.*, u.id as user_id, w.*, w.id as wallet_id
            FROM referral_chain as r
            INNER JOIN users as u ON u.id=r.user_id
            INNER JOIN wallet as w ON w.user_id=u.id
            WHERE r.referred_user_id=:referred_user_id",
            "single",
            [
                ":referred_user_id" => $referredById
            ]
        );
        if(!$group || $group === null)
        {
            return;
        }

        if(
            (float) $commissions[$count]["commission"] >= (float) 0
        )
        {
            $groupWalletAmount = (float) $group["amount"]
                + ((
                    (float) round($commissions[$count]["commission"] / 100, 2))
                    * $product->price
                );

            $this
                ->userWalletService
                ->updateWallet(
                    $group["id"],
                    $group["user_id"],
                    $groupWalletAmount
                );

        }
        $count += 1;
        $this->assignCommissionsToGroups(
            $group["user_id"],
            $commissions,
            $product,
            $count
        );
    }
}
