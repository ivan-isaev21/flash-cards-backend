<?php

namespace App\Infrastructure\Repositories;

use App\Application\Shared\ValueObjects\Email;
use App\Application\User\ValueObjects\UserId;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepository;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\DB;

class EloquentUserRepository implements UserRepository
{

    public function findUserById(UserId $id): ?User
    {
        $userModel = UserModel::find($id->getValue());

        if ($userModel === null) {
            return null;
        }

        return $userModel->mapToEntity();
    }

    public function findUserByEmail(Email $email): ?User
    {
        $userModel = UserModel::where('email', $email->getValue())->first();

        if ($userModel === null) {
            return null;
        }

        return $userModel->mapToEntity();
    }

    public function register(User $user): User
    {
        $createdUserModel = DB::transaction(function () use ($user) {
            return UserModel::create([
                'id' => $user->id->getValue(),
                'name' => $user->name,
                'email' => $user->email->getValue(),
                'password' => $user->password,
                'email_verified_at' => null,
                'verified_token' => $user->verifiedToken->toArray(),
                'createdAt' => $user->createdAt,
                'updatedAt' => $user->updatedAt,
            ]);
        });
        return $createdUserModel->mapToEntity();
    }

    public function save(User $user): User
    {
        $updatedUserModel = DB::transaction(function () use ($user) {
            $userModel = UserModel::findOrFail($user->id->getValue());

            $userModel->update([
                'name' => $user->name,
                'email' => $user->email->getValue(),
                'password' => $user->password,
                'email_verified_at' => $user->emailVerifiedAt,
                'verified_token' => $user->verifiedToken !== null ? $user->verifiedToken->toArray() : null,
                'createdAt' => $user->createdAt,
                'updatedAt' => $user->updatedAt,
            ]);

            return $userModel;
        });

        return $updatedUserModel->mapToEntity();
    }
}
