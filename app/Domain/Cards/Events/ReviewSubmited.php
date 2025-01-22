<?php

namespace App\Domain\Cards\Events;

use App\Application\Cards\ValueObjects\SpacedRepetitionId;

class ReviewSubmited
{
    public function __construct(
        public readonly SpacedRepetitionId $id
    ) {}
}
