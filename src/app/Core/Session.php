<?php

namespace App\Core;

use App\Core\Interfaces\SessionInterface;

class Session implements SessionInterface
{
    public function get(string $key): string | null
    {
        if(isset($_SESSION[$key]))
        {
            return $_SESSION[$key];
        }

        return null;
    }

    public function set(
        string $key,
        string $value
    ): string | null
    {
        if(
            strlen($key) > 0 &&
            strlen($value) > 0 
        )
        {
            $_SESSION[$key] = $value;
            return $_SESSION[$key];
        }

        return null;
    }

    public function startSession(): bool
    {
        return session_start();
    }

    public function sessionStatus(): int
    {
        return session_status();
    }

    public function destroySession(): bool
    {
        return session_destroy();
    }

    public function sessionUnset(): bool
    {
        return session_unset();
    }

    public function isEmpty(): bool
    {
        if(empty($_SESSION))
        {
            return true;
        }
        return false;
    }
}
