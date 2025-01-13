<?php

namespace App\Application\Cards\Commands;

use App\Application\Shared\Enums\Locale;
use App\Application\User\ValueObjects\UserId;

class CreateCardCommand
{
    public function __construct(
        public readonly Locale $locale,
        public readonly string $question,
        public readonly string $answer,
        public readonly array $keywords,
        public readonly UserId $createdBy
    ) {}
}
