<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Middleware;

class AuthMiddleware extends Middleware
{
    public function handle(): null | bool
    {
        if(!Auth::check())
        {
            static::$isContinue = false;
            return redirect("/login");
        }
        return true;
    }
}
