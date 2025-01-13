<?php

namespace App\Application\Cards\Handlers;

use App\Application\Cards\Commands\DeleteCardCommand;
use App\Domain\Cards\Repositories\CardRepository;

class DeleteCardHandler
{
    private CardRepository $repository;

    public function __construct(CardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(DeleteCardCommand $command): void
    {
        $this->repository->delete($command->id);
    }
}
