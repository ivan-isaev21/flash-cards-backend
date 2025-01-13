<?php

namespace App\Application\Cards\Handlers;

use App\Application\Cards\Commands\DeleteDeckItemCommand;
use App\Domain\Cards\Repositories\DeckItemRepository;

class DeleteDeckItemHandler
{
    private DeckItemRepository $repository;

    public function __construct(DeckItemRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(DeleteDeckItemCommand $command): void
    {
        $this->repository->delete($command->id);
    }
}
