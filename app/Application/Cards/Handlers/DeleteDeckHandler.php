<?php

namespace App\Application\Cards\Handlers;

use App\Application\Cards\Commands\DeleteDeckCommand;
use App\Domain\Cards\Repositories\DeckRepository;

class DeleteDeckHandler
{
    private DeckRepository $repository;

    public function __construct(DeckRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(DeleteDeckCommand $command): void
    {
        $this->repository->delete($command->id);
    }
}
