<?php

namespace App\Application\Cards\Commands;

use App\Application\Cards\ValueObjects\CardId;

class DeleteCardCommand
{
    public function __construct(
        public readonly CardId $id,
    ) {}
}
