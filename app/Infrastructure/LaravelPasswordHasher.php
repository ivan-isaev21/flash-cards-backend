<?php

namespace App\Infrastructure;

use App\Domain\User\Contracts\PasswordHasher;
use Illuminate\Support\Facades\Hash;

class LaravelPasswordHasher implements PasswordHasher
{
    public function make(string $password): string
    {
        return Hash::make($password);
    }

    public function verify(string $password, string $hash): bool
    {
        return Hash::check($password, $hash);
    }
}
