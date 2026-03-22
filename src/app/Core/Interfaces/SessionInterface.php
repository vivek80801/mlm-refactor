<?php

namespace App\Core\Interfaces;

interface SessionInterface
{
    public function get(string $key): string | null;
    public function set(string $key, string $value): string | null;
    public function startSession():bool;
    public function sessionStatus():int;
    public function destroySession():bool;
    public function sessionUnset():bool;
}
