<?php

namespace App\Domain\Cards\Services;

use App\Application\Cards\Commands\CreateCardCommand;
use App\Application\Cards\Commands\DeleteCardCommand;
use App\Application\Cards\Commands\DublicateCardCommand;
use App\Application\Cards\Commands\UpdateCardCommand;
use App\Application\Cards\Handlers\CreateCardHandler;
use App\Application\Cards\Handlers\DeleteCardHandler;
use App\Application\Cards\Handlers\DublicateCardHandler;
use App\Application\Cards\Handlers\GetCardsHandler;
use App\Application\Cards\Handlers\UpdateCardHandler;
use App\Application\Cards\Queries\GetCardsQuery;
use App\Domain\Cards\Entities\Card;
use App\Domain\Cards\Events\CardCreated;
use App\Domain\Cards\Events\CardDeleted;
use App\Domain\Cards\Events\CardDublicated;
use App\Domain\Cards\Events\CardUpdated;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CardService
{
    private GetCardsHandler $getCardsHandler;
    private CreateCardHandler $createCardHandler;
    private UpdateCardHandler $updateCardHandler;
    private DublicateCardHandler $dublicateCardHandler;
    private DeleteCardHandler $deleteCardHandler;
    private Dispatcher $dispatcher;

    public function __construct(
        Dispatcher $dispatcher,
        GetCardsHandler $getCardsHandler,
        CreateCardHandler $createCardHandler,
        UpdateCardHandler $updateCardHandler,
        DublicateCardHandler $dublicateCardHandler,
        DeleteCardHandler $deleteCardHandler
    ) {
        $this->dispatcher = $dispatcher;
        $this->getCardsHandler = $getCardsHandler;
        $this->createCardHandler = $createCardHandler;
        $this->updateCardHandler = $updateCardHandler;
        $this->dublicateCardHandler = $dublicateCardHandler;
        $this->deleteCardHandler = $deleteCardHandler;
    }

    public function paginate(GetCardsQuery $query): LengthAwarePaginator
    {
        return $this->getCardsHandler->handle($query);
    }

    public function create(CreateCardCommand $command): Card
    {
        $card = $this->createCardHandler->handle($command);
        $this->dispatcher->dispatch(new CardCreated($card->id));
        return $card;
    }

    public function update(UpdateCardCommand $command): Card
    {
        $card = $this->updateCardHandler->handle($command);
        $this->dispatcher->dispatch(new CardUpdated($card->id));
        return $card;
    }

    public function dublicate(DublicateCardCommand $command): Card
    {
        $card = $this->dublicateCardHandler->handle($command);
        $this->dispatcher->dispatch(new CardDublicated(
            originalId: $command->id,
            dublicatedId: $card->id
        ));
        return $card;
    }

    public function delete(DeleteCardCommand $command): void
    {
        $this->deleteCardHandler->handle($command);
        $this->dispatcher->dispatch(new CardDeleted($command->id));
    }
}
