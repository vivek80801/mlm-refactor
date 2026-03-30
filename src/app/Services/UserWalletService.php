<?php

namespace App\Services;

use App\Models\Wallet;

class  UserWalletService
{
    public function createWallet
    (
        int $userId
    ): void
    {
        $wallet = new Wallet();
        $wallet->user_id = $userId;
        $wallet->amount = 0;
        $wallet->type = "cr";
        $wallet->save();
    }
    public function updateWallet
    (
        int $walletId,
        int $userId,
        int $amount
    )
    {
        $wallet = new Wallet();
        $wallet->id = $walletId;
        $wallet->user_id = $userId;
        $wallet->amount = $amount;
        $wallet->save();
    }
}
