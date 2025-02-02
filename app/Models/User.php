<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Application\Shared\ValueObjects\Email;
use App\Application\User\ValueObjects\Token;
use App\Application\User\ValueObjects\UserId;
use App\Domain\User\Entities\User as UserEntity;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'verified_token',
        'email_verified_at',
        'updated_at',
        'created_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verified_token' => 'array',
            'password' => 'hashed',
        ];
    }

    public function mapToEntity(): UserEntity
    {
        return new UserEntity(
            id: new UserId($this->id),
            name: $this->name,
            email: new Email($this->email),
            password: $this->password,
            emailVerifiedAt: new DateTimeImmutable($this->email_verified_at),
            verifiedToken: $this->verified_token !== null ?
                new Token(
                    value: $this->verified_token['value'],
                    type: $this->verified_token['type'],
                    createdAt: new DateTimeImmutable($this->verified_token['createdAt']),
                    expiredAt: $this->verified_token['expiredAt'] !== null ? new DateTimeImmutable($this->verified_token['expiredAt']) : null
                ) : null,
            createdAt: new DateTimeImmutable($this->created_at),
            updatedAt: new DateTimeImmutable($this->updated_at)
        );
    }
}
