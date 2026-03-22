<?php

namespace App\Models;

use App\Core\Models;

class User extends Models
{
    protected static string $table = 'users';

    public int $id;
    public int $mobile;
    public string $password;
    public string $referal_code;
    public int | null $otp;
    public int | null $referred_by;
    public string $referal_qr;
    public string | null $invite_code;
    public string $is_banned;
}

