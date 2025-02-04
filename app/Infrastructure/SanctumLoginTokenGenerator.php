<?php

namespace App\Infrastructure;


use App\Application\User\ValueObjects\UserId;
use App\Application\User\ValueObjects\Token;
use App\Domain\User\Contracts\LoginTokenGenerator;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Models\User as UserModel;
use DateTimeImmutable;

class SanctumLoginTokenGenerator implements LoginTokenGenerator
{
    public function generate(UserId $id): Token
    {
        $user = UserModel::find($id->getValue());

        if ($user === null) {
            throw new UserNotFoundException($id);
        }

        $token = $user->createToken('api')->plainTextToken;

        return new Token(value: $token, type: 'Bearer', createdAt: new DateTimeImmutable('now'));
    }

    public function delete(UserId $id, string $token): void
    {
        $user = UserModel::find($id->getValue());

        if ($user === null) {
            throw new UserNotFoundException($id);
        }

        $user->tokens()->where('token', $token)->delete();
    }

    public function deleteAll(UserId $id): void
    {
        $user = UserModel::find($id->getValue());

        if ($user === null) {
            throw new UserNotFoundException($id);
        }

        $user->tokens()->delete();
    }
}
