<?php

namespace App\Application\Cards\Commands;

use App\Application\Cards\ValueObjects\CardId;
use App\Application\User\ValueObjects\UserId;

class DublicateCardCommand
{
    public function __construct(
        public readonly CardId $id,
        public readonly UserId $createdBy
    ) {}
}
