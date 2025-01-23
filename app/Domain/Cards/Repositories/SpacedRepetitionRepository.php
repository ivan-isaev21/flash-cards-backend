<?php

namespace App\Domain\Cards\Repositories;

use App\Application\Cards\ValueObjects\DeckId;
use App\Application\Cards\ValueObjects\DeckItemId;
use App\Application\Cards\ValueObjects\SpacedRepetitionId;
use App\Application\User\ValueObjects\UserId;
use App\Domain\Cards\Entities\DeckItem;
use App\Domain\Cards\Entities\SpacedRepetition;

interface SpacedRepetitionRepository
{
    public function findFirstUnstudiedSpacedRepetition(DeckId $deckId, UserId $userId): ?SpacedRepetition;
    public function findSpacedRepetitionByDeckIdAndUserId(DeckItemId $deckItemId, UserId $userId): ?SpacedRepetition;
    public function save(SpacedRepetition $spacedRepetition): SpacedRepetition;
    public function delete(SpacedRepetitionId $id): void;
}
