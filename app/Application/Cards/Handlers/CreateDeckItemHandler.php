<?php

namespace App\Application\Cards\Handlers;

use App\Application\Cards\Commands\CreateDeckItemCommand;
use App\Application\Cards\ValueObjects\DeckItemId;
use App\Domain\Cards\Entities\DeckItem;
use App\Domain\Cards\Exceptions\CardNotFoundException;
use App\Domain\Cards\Exceptions\DeckItemInvalidArgumentException;
use App\Domain\Cards\Exceptions\DeckNotFoundException;
use App\Domain\Cards\Repositories\CardRepository;
use App\Domain\Cards\Repositories\DeckItemRepository;
use App\Domain\Cards\Repositories\DeckRepository;

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
        $card = $this->cardRepository->findCardById($command->cardId);

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
            id: DeckItemId::next(),
            deck: $deck,
            card: $card
        );

        return $this->repository->create($deckItem);
    }
}
