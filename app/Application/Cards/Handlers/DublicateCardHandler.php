<?php

namespace App\Application\Cards\Handlers;

use App\Application\Cards\Commands\DublicateCardCommand;
use App\Application\Cards\ValueObjects\CardId;
use App\Domain\Cards\Entities\Card;
use App\Domain\Cards\Exceptions\CardNotFoundException;
use App\Domain\Cards\Repositories\CardRepository;
use DateTimeImmutable;

class DublicateCardHandler
{
    private CardRepository $repository;

    public function __construct(CardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(DublicateCardCommand $command): Card
    {
        $card = $this->repository->findCardById($command->id);

        if ($card === null) {
            throw new CardNotFoundException("Card with id : " . $command->id->getValue() . 'does not exist!');
        }

        $dublicatedCard = new Card(
            id: CardId::next(),
            locale: $card->locale,
            question: $card->question,
            answer: $card->answer,
            keywords: $card->keywords,
            createdBy: $command->createdBy,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );

        return $this->repository->create($dublicatedCard);
    }
}
