<?php

namespace App\Services;

use App\Models\Bonus;

class  BonusService
{
    public function createBonus
    (
        string $bonusType,
        int $userId,
        float $amount,
    )
    {
        $bonusInsert = new Bonus();
        $bonusInsert->bonus_type = $bonusType;
        $bonusInsert->user_id = $userId;
        $bonusInsert->amount = $amount;
        $bonusInsert->save();
    }
}
