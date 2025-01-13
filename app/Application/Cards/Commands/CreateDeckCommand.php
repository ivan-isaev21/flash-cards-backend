<?php

namespace App\Application\Cards\Commands;

use App\Application\Cards\Enums\DeckType;
use App\Application\User\ValueObjects\UserId;

class CreateDeckCommand
{
    public function __construct(
        public readonly string $name,
        public readonly DeckType $type,
        public readonly UserId $createdBy,
    ) {}
}
