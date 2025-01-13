<?php

namespace App\Application\Cards\Handlers;

use App\Application\Cards\Queries\GetDeckItemsQuery;
use App\Domain\Cards\Repositories\DeckItemRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetDeckItemsHandler
{
    private DeckItemRepository $repository;

    public function __construct(DeckItemRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetDeckItemsQuery $query): LengthAwarePaginator
    {
        return $this->repository->paginate(
            deckId: $query->deckId,
            page: $query->page,
            perPage: $query->perPage
        );
    }
}
