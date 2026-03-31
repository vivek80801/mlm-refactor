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
        return Product::all();
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
        $productQuery = Product::query()
            ->where("id", $productId)
            ->get()
        ;

        if(count($productQuery) <= 0)
        {
            throw new AppException("Product Error: Product Not Found");
        }

        $product = $productQuery[0];

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
            "is_product_can_be_purchased" =>
            $purchasedProduct["product_count"] > 0 
            ? false : true,
        ];
    }

    public function productRental(
        int $productId,
        int $userId,
        ?string $inviteCode
    ): void
    {
        $this->validateInviteCode($inviteCode);
    
        $wallet = $this->getUserWallet($userId);
        $product = $this->getProduct($productId);
    
        $this->ensureSufficientBalance($wallet, $product);
    
        ProductPurchase::transaction(function ()
            use (
                $userId, $inviteCode, $wallet, $product
            ) {
            $referrer = $this->getReferrer($inviteCode);
    
            $bonusAmount = $this->handleWelcomeBonus($userId);
    
                $this->deductUserBalance(
                    $wallet,
                    $product,
                    $bonusAmount
                );
            $this->rewardReferrer($referrer, $product);
    
            $this->productPurchaseService->createProductPurchase($product->id, $userId);
    
            $this->distributeCommissions($referrer->id, $product);
        });
    }

    private function validateInviteCode(?string $code): void
    {
        if (!$code) {
            throw new AppException("Invite code required");
        }
    }

    private function getProduct(int $id): Product
    {
        $product = Product::query()->find($id);
    
        if (!$product) {
            throw new AppException("Product not found");
        }
    
        return $product;
    }
    private function getUserWallet(int $userId): Wallet
    {
        $wallet = Wallet::query()
            ->where("user_id", $userId)
            ->first();
    
        if (!$wallet) {
            throw new AppException("Wallet not found");
        }
    
        return $wallet;
    }

    private function ensureSufficientBalance(
        Wallet $wallet,
        Product $product
    ): void
    {
        if ($wallet->amount < $product->price) {
            throw new AppException("Insufficient balance");
        }
    }

    private function handleWelcomeBonus(int $userId): float
    {
        $hasPurchased = ProductPurchase::query()
            ->where("user_id", $userId)
            ->exists();
    
        if ($hasPurchased) {
            return 0;
        }
    
        $bonus = User::sql("SELECT amount FROM welcome_bonus_by_admin");
    
        $amount = (float) $bonus["amount"];
    
        $this->bonusService->createBonus("welcome", $userId, $amount);
    
        return $amount;
    }

    private function deductUserBalance(
        Wallet $wallet,
        Product $product,
        float $bonus
    ): void
    {
        $newAmount = $wallet->amount - $product->price + $bonus;
    
        $this->userWalletService->updateWallet(
            $wallet->id,
            $wallet->user_id,
            $newAmount
        );
    }

    private function rewardReferrer(
        User $referrer,
        Product $product
    ): void
    {
        $wallet = $this->getUserWallet($referrer->id);
    
        $newAmount = $wallet->amount + $product->referal_bonus;
    
        $this->userWalletService->updateWallet(
            $wallet->id,
            $referrer->id,
            $newAmount
        );
    }

    private function distributeCommissions(
        int $userId,
        Product $product
    ): void
    {
        $commissions = User::sql("SELECT * FROM commission_for_groups", "multi");
    
        foreach ($commissions as $level => $commission) {
            $group = $this->getReferralGroup($userId);
    
            if (!$group) {
                break;
            }
    
            $amount = $group["amount"] +
                (($commission["commission"] / 100) * $product->price);
    
            $this->userWalletService->updateWallet(
                $group["wallet_id"],
                $group["user_id"],
                $amount
            );
    
            $userId = $group["user_id"];
        }
    }

    private function getReferralGroup(int $userId): ?array
    {
        return User::sql(
            "SELECT r.*, u.id as user_id, w.id as wallet_id, w.amount
             FROM referral_chain r
             JOIN users u ON u.id = r.user_id
             JOIN wallet w ON w.user_id = u.id
             WHERE r.referred_user_id = :id",
            "single",
            [":id" => $userId]
        );
    }

    private function getReferrer(string $inviteCode): User
    {
        $referrer = User::query()
            ->where("referral_code", $inviteCode)
            ->first();
    
        if (!$referrer) {
            throw new AppException("Referrer not found");
        }
    
        return $referrer;
    }
}
