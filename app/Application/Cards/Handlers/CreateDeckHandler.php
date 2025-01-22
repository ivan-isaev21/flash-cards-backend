<?php

namespace App\Application\Cards\Handlers;

use App\Application\Cards\Commands\CreateDeckCommand;
use App\Application\Cards\ValueObjects\DeckId;
use App\Domain\Cards\Entities\Deck;
use App\Domain\Cards\Exceptions\DeckWithNameAlreadyExistsException;
use App\Domain\Cards\Repositories\DeckRepository;

class CreateDeckHandler
{
    private DeckRepository $repository;

    public function __construct(DeckRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(CreateDeckCommand $command): Deck
    {
        if ($this->repository->findDeckByName($command->name) !== null) {
            throw new DeckWithNameAlreadyExistsException($command->name);
        }

        $deck = new Deck(
            id: DeckId::next(),
            locale: $command->locale,
            name: $command->name,
            type: $command->type,
            createdBy: $command->createdBy
        );

        return $this->repository->create($deck);
    }
}
