<?php

namespace App\Application\Cards\Handlers;

use App\Application\Cards\Commands\UpdateCardCommand;
use App\Domain\Cards\Entities\Card;
use App\Domain\Cards\Exceptions\CardNotFoundException;
use App\Domain\Cards\Repositories\CardRepository;
use DateTimeImmutable;

class UpdateCardHandler
{
    private CardRepository $repository;

    public function __construct(CardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(UpdateCardCommand $command): Card
    {
        $card = $this->repository->findCardById($command->id);

        if ($card === null) {
            throw new CardNotFoundException("Card with id : " . $command->id->getValue() . 'does not exist!');
        }

        $updatedCard = new Card(
            id: $card->id,
            locale: $command->locale ?? $card->locale,
            question: $command->question ?? $card->question,
            answer: $command->answer ?? $card->answer,
            keywords: $command->keywords ?? $card->keywords,
            createdBy: $command->createdBy,
            createdAt: $card->createdAt,
            updatedAt: new DateTimeImmutable(),
        );

        return $this->repository->update($updatedCard);
    }
}
