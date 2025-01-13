<?php

namespace App\Application\Cards\Handlers;

use App\Application\Cards\Commands\CreateCardCommand;
use App\Application\Cards\ValueObjects\CardId;
use App\Domain\Cards\Entities\Card;
use App\Domain\Cards\Repositories\CardRepository;
use DateTimeImmutable;

class CreateCardHandler
{
    private CardRepository $repository;

    public function __construct(CardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(CreateCardCommand $command): Card
    {
        $card = new Card(
            id: CardId::next(),
            locale: $command->locale,
            question: $command->question,
            answer: $command->answer,
            keywords: $command->keywords,
            createdBy: $command->createdBy,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );

        return  $this->repository->create($card);
    }
}
