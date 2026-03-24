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
}
