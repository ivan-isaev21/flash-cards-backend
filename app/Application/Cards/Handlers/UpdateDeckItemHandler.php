<?php

namespace App\Application\Cards\Handlers;

use App\Application\Cards\Commands\UpdateDeckItemCommand;
use App\Domain\Cards\Entities\DeckItem;
use App\Domain\Cards\Exceptions\CardNotFoundException;
use App\Domain\Cards\Exceptions\DeckItemInvalidArgumentException;
use App\Domain\Cards\Exceptions\DeckItemNotFoundException;
use App\Domain\Cards\Exceptions\DeckNotFoundException;
use App\Domain\Cards\Repositories\CardRepository;
use App\Domain\Cards\Repositories\DeckItemRepository;
use App\Domain\Cards\Repositories\DeckRepository;

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
        $card = $command->cardId !== null ? $this->cardRepository->findCardById($command->cardId) : $deckItem->card;

        if ($deck === null) {
            throw new DeckNotFoundException($command->deckId);
        }

        if ($card === null) {
            throw new CardNotFoundException($command->cardId);
        }

        if ($deck->locale->value !== $card->locale->value) {
            throw new DeckItemInvalidArgumentException("Deck locale and card locale is not equals!");
        }

        $deckItem = new DeckItem(
            id: $deckItem->id,
            deck: $deck,
            card: $card
        );

        return $this->repository->update($deckItem);
    }
}
