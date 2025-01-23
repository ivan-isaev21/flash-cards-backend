<?php

namespace App\Application\Cards\Handlers;

use App\Application\Cards\Queries\GetCardToReviewQuery;
use App\Domain\Cards\Entities\DeckItem;
use App\Domain\Cards\Repositories\DeckItemRepository;
use App\Domain\Cards\Repositories\SpacedRepetitionRepository;

class GetCardToReviewHandler
{
    private DeckItemRepository $deckItemRepository;
    private SpacedRepetitionRepository $spacedRepetitionRepository;

    public function __construct(DeckItemRepository $deckItemRepository, SpacedRepetitionRepository $spacedRepetitionRepository)
    {
        $this->deckItemRepository = $deckItemRepository;
        $this->spacedRepetitionRepository = $spacedRepetitionRepository;
    }

    public function handle(GetCardToReviewQuery $query): ?DeckItem
    {
        $spacedRepetition = $this->spacedRepetitionRepository->findFirstUnstudiedSpacedRepetition(deckId: $query->deckId, userId: $query->userId);

        if ($spacedRepetition !== null) {
            return $spacedRepetition->deckItem;
        }

        return $this->deckItemRepository->findFirstUnstudiedDeckItem(deckId: $query->deckId, userId: $query->userId);
    }
}
