<?php

namespace App\Application\Cards\Handlers;


use App\Application\Cards\Commands\UpdateDeckCommand;
use App\Domain\Cards\Entities\Deck;
use App\Domain\Cards\Exceptions\DeckNotFoundException;
use App\Domain\Cards\Repositories\DeckRepository;

class UpdateDeckHandler
{
    private DeckRepository $repository;

    public function __construct(DeckRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(UpdateDeckCommand $command): Deck
    {
        $deck = $this->repository->findDeckById($command->id);

        if ($deck === null) {
            throw new DeckNotFoundException($command->id);
        }

        $updatedDeck = new Deck(
            id: $deck->id,
            locale: $command->locale ?? $deck->locale,
            name: $command->name ?? $deck->name,
            type: $command->type ?? $deck->type,
            createdBy: $command->createdBy
        );


        return $this->repository->update($updatedDeck);
    }
}
