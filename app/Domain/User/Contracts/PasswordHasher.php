<?php

namespace App\Domain\User\Contracts;

interface PasswordHasher
{
    public function make(string $password): string;
    public function verify(string $password, string $hash): bool;
}
