<?php

namespace App\Application\Cards\Handlers;

use App\Application\Cards\Commands\UpdateDeckItemCommand;
use App\Domain\Cards\Entities\DeckItem;
use App\Domain\Cards\Exceptions\CardNotFoundException;
use App\Domain\Cards\Exceptions\DeckItemNotFoundException;
use App\Domain\Cards\Exceptions\DeckNotFoundException;
use App\Domain\Cards\Repositories\CardRepository;
use App\Domain\Cards\Repositories\DeckItemRepository;
use App\Domain\Cards\Repositories\DeckRepository;
use DateTimeImmutable;

class UpdateDeckItemHandler
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

    public function handle(UpdateDeckItemCommand $command): DeckItem
    {
        $deckItem = $this->repository->findDeckItemById($command->id);

        if ($deckItem === null) {
            throw new DeckItemNotFoundException($command->id);
        }

        $deck = $command->deckId !== null ? $this->deckRepository->findDeckById($command->deckId) : $deckItem->deck;
        $card = $command->cardId !== null ? $this->cardRepository->findDeckById($command->cardId) : $deckItem->card;


        if ($deck === null) {
            throw new DeckNotFoundException($command->deckId);
        }

        if ($card === null) {
            throw new CardNotFoundException($command->cardId);
        }

        $deckItem = new DeckItem(
            id: $deckItem->id,
            deck: $deck,
            card: $card,
            createdAt: $deckItem->createdAt,
            updatedAt: new DateTimeImmutable(),
        );

        return $this->repository->update($deckItem);
    }
}
