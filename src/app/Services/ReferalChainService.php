<?php

namespace App\Services;

use App\Models\ReferalChain;

class  ReferalChainService
{
    public function addToReferalChain
    (
        int $userId,
        int $referedByUserID,
    ): void
    {
        $referalChain = new ReferalChain();
        $referalChain->user_id = $userId;
        $referalChain
        ->referred_user_id
        = $referedByUserID;
        $referalChain->save();
    }
}
