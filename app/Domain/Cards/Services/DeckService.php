<?php

namespace App\Domain\Cards\Services;

use App\Application\Cards\Commands\CreateDeckCommand;
use App\Application\Cards\Commands\DeleteDeckCommand;
use App\Application\Cards\Commands\UpdateDeckCommand;
use App\Application\Cards\Handlers\CreateDeckHandler;
use App\Application\Cards\Handlers\DeleteDeckHandler;
use App\Application\Cards\Handlers\GetDecksHandler;
use App\Application\Cards\Handlers\UpdateDeckHandler;
use App\Application\Cards\Queries\GetDecksQuery;
use App\Domain\Cards\Entities\Deck;
use App\Domain\Cards\Events\DeckCreated;
use App\Domain\Cards\Events\DeckDeleted;
use App\Domain\Cards\Events\DeckUpdated;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DeckService
{
    private GetDecksHandler $getDecksHandler;
    private CreateDeckHandler $createDeckHandler;
    private UpdateDeckHandler $updateDeckHandler;
    private DeleteDeckHandler $deleteDeckHandler;
    private Dispatcher $dispatcher;

    public function __construct(
        Dispatcher $dispatcher,
        GetDecksHandler $getDecksHandler,
        CreateDeckHandler $createDeckHandler,
        UpdateDeckHandler $updateDeckHandler,
        DeleteDeckHandler $deleteDeckHandler
    ) {
        $this->dispatcher = $dispatcher;
        $this->getDecksHandler = $getDecksHandler;
        $this->createDeckHandler = $createDeckHandler;
        $this->updateDeckHandler = $updateDeckHandler;
        $this->deleteDeckHandler = $deleteDeckHandler;
    }

    public function paginate(GetDecksQuery $query): LengthAwarePaginator
    {
        return $this->getDecksHandler->handle($query);
    }

    public function create(CreateDeckCommand $command): Deck
    {
        $deck = $this->createDeckHandler->handle($command);
        $this->dispatcher->dispatch(new DeckCreated($deck->id));
        return $deck;
    }

    public function update(UpdateDeckCommand $command): Deck
    {
        $deck = $this->updateDeckHandler->handle($command);
        $this->dispatcher->dispatch(new DeckUpdated($deck->id));
        return $deck;
    }

    public function delete(DeleteDeckCommand $command): void
    {
        $this->deleteDeckHandler->handle($command);
        $this->dispatcher->dispatch(new DeckDeleted($command->id));
    }
}
