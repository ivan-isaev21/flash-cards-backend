<?php

namespace App\Application\Cards\Commands;

use App\Application\Cards\ValueObjects\CardId;
use App\Application\Shared\Enums\Locale;
use App\Application\User\ValueObjects\UserId;

class UpdateCardCommand
{
    public function __construct(
        public readonly CardId $id,
        public readonly UserId $createdBy,
        public readonly ?Locale $locale = null,
        public readonly ?string $question = null,
        public readonly ?string $answer = null,
        public readonly ?array $keywords = null,

    ) {}
}
