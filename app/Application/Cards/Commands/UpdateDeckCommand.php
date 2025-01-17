<?php

namespace App\Application\Cards\Commands;

use App\Application\Cards\Enums\DeckType;
use App\Application\Cards\ValueObjects\DeckId;
use App\Application\Shared\Enums\Locale;
use App\Application\User\ValueObjects\UserId;

class UpdateDeckCommand
{
    public function __construct(
        public readonly DeckId $id,
        public readonly UserId $createdBy,
        public readonly ?Locale $locale = null,
        public readonly ?string $name = null,
        public readonly ?DeckType $type = null,
    ) {}
}
