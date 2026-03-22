<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    public static function attempts
    (
        string $mobile,
        string $password,
    )
    : bool
    {
        $user = (Object) User::sql(
            "SELECT * FROM users WHERE mobile=:mobile",
            "single",
            [
                ":mobile" => $mobile
            ]
        );

        if(
            password_verify(
                $password, $user->password
            )
        )
        {
            $session = new Session();
            $fields = array_keys((Array) $user);
            foreach($fields as $field)
            {
                if(
                    $field !== null &&
                    $user->$field !== null
                )
                {
                    $session->set($field, $user->$field);
                }
            }
            return true;
        }
        return false;
    }

    public static function check(): bool
    {
        $session = new Session();
        if($session->isEmpty())
        {
            return false;
        }
        return true;
    }

    public static function logout():void
    {
        $session = new Session();
        $session->sessionUnset();
        $session->destroySession();
    }
}
