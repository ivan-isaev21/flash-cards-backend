<?php

namespace App\Application\Cards\Handlers;

use App\Application\Cards\Commands\CreateDeckItemCommand;
use App\Application\Cards\ValueObjects\DeckItemId;
use App\Domain\Cards\Entities\DeckItem;
use App\Domain\Cards\Exceptions\CardNotFoundException;
use App\Domain\Cards\Exceptions\DeckNotFoundException;
use App\Domain\Cards\Repositories\CardRepository;
use App\Domain\Cards\Repositories\DeckItemRepository;
use App\Domain\Cards\Repositories\DeckRepository;
use DateTimeImmutable;

class CreateDeckItemHandler
{
    private DeckItemRepository $repository;
    private DeckRepository $deckRepository;
    private CardRepository $cardRepository;

    public function __construct(DeckItemRepository $repository, DeckRepository $deckRepository, CardRepository $cardRepository)
    {
        $this->repository = $repository;
        $this->deckRepository = $deckRepository;
        $this->cardRepository = $cardRepository;
    }

    public function handle(CreateDeckItemCommand $command): DeckItem
    {
        $deck = $this->deckRepository->findDeckById($command->deckId);
        $card = $this->cardRepository->findDeckById($command->cardId);

        if ($deck === null) {
            throw new DeckNotFoundException($command->deckId);
        }

        if ($card === null) {
            throw new CardNotFoundException($command->cardId);
        }

        $deckItem = new DeckItem(
            id: DeckItemId::next(),
            deck: $deck,
            card: $card,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );

        return $this->repository->create($deckItem);
    }
}
