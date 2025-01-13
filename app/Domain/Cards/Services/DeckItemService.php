<?php

namespace App\Domain\Cards\Services;

use App\Application\Cards\Commands\CreateDeckItemCommand;
use App\Application\Cards\Commands\DeleteDeckItemCommand;
use App\Application\Cards\Commands\UpdateDeckItemCommand;
use App\Application\Cards\Handlers\CreateDeckItemHandler;
use App\Application\Cards\Handlers\DeleteDeckItemHandler;
use App\Application\Cards\Handlers\GetDeckItemsHandler;
use App\Application\Cards\Handlers\UpdateDeckItemHandler;
use App\Application\Cards\Queries\GetDeckItemsQuery;
use App\Domain\Cards\Entities\DeckItem;
use App\Domain\Cards\Events\DeckItemCreated;
use App\Domain\Cards\Events\DeckItemDeleted;
use App\Domain\Cards\Events\DeckItemUpdated;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DeckItemService
{
    private GetDeckItemsHandler $getDeckItemsHandler;
    private CreateDeckItemHandler $createDeckItemHandler;
    private UpdateDeckItemHandler $updateDeckItemHandler;
    private DeleteDeckItemHandler $deleteDeckItemHandler;
    private Dispatcher $dispatcher;

    public function __construct(
        Dispatcher $dispatcher,
        GetDeckItemsHandler $getDeckItemsHandler,
        CreateDeckItemHandler $createDeckItemHandler,
        UpdateDeckItemHandler $updateDeckItemHandler,
        DeleteDeckItemHandler $deleteDeckItemHandler
    ) {
        $this->dispatcher = $dispatcher;
        $this->getDeckItemsHandler = $getDeckItemsHandler;
        $this->createDeckItemHandler = $createDeckItemHandler;
        $this->updateDeckItemHandler = $updateDeckItemHandler;
        $this->deleteDeckItemHandler = $deleteDeckItemHandler;
    }

    public function paginate(GetDeckItemsQuery $query): LengthAwarePaginator
    {
        return $this->getDeckItemsHandler->handle($query);
    }

    public function create(CreateDeckItemCommand $command): DeckItem
    {
        $deckItem = $this->createDeckItemHandler->handle($command);
        $this->dispatcher->dispatch(new DeckItemCreated($deckItem->id));
        return  $deckItem;
    }

    public function update(UpdateDeckItemCommand $command): DeckItem
    {
        $deckItem  = $this->updateDeckItemHandler->handle($command);
        $this->dispatcher->dispatch(new DeckItemUpdated($deckItem->id));
        return  $deckItem;
    }

    public function delete(DeleteDeckItemCommand $command): void
    {
        $this->deleteDeckItemHandler->handle($command);
        $this->dispatcher->dispatch(new DeckItemDeleted($command->id));
    }
}
