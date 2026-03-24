<?php

namespace App\Models;

use App\Core\Models;

class  ReferalChain extends Models
{
    protected static string $table = 'referral_chain';

    public int $id;
    public int $user_id;
    public int $referred_user_id;
}
