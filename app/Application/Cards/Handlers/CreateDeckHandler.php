<?php

namespace App\Application\Cards\Handlers;

use App\Application\Cards\Commands\CreateDeckCommand;
use App\Application\Cards\ValueObjects\DeckId;
use App\Domain\Cards\Entities\Deck;
use App\Domain\Cards\Repositories\DeckRepository;
use DateTimeImmutable;

class CreateDeckHandler
{
    private DeckRepository $repository;

    public function __construct(DeckRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(CreateDeckCommand $command): Deck
    {
        $deck = new Deck(
            id: DeckId::next(),
            name: $command->name,
            type: $command->type,
            createdBy: $command->createdBy,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );

        return $this->repository->create($deck);
    }
}
