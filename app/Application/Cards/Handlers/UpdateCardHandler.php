<?php

namespace App\Application\Cards\Handlers;

use App\Application\Cards\Commands\UpdateCardCommand;
use App\Domain\Cards\Entities\Card;
use App\Domain\Cards\Exceptions\CardNotFoundException;
use App\Domain\Cards\Repositories\CardRepository;

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
            throw new CardNotFoundException($command->id);
        }

        $updatedCard = new Card(
            id: $card->id,
            locale: $command->locale ?? $card->locale,
            question: $command->question ?? $card->question,
            answer: $command->answer ?? $card->answer,
            keywords: $command->keywords ?? $card->keywords,
            createdBy: $command->createdBy,
        );

        return $this->repository->update($updatedCard);
    }
}
