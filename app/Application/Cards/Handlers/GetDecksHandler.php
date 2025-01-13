<?php

namespace App\Application\Cards\Handlers;

use App\Application\Cards\Queries\GetDecksQuery;
use App\Domain\Cards\Repositories\DeckRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetDecksHandler
{
    private DeckRepository $repository;

    public function __construct(DeckRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetDecksQuery $query): LengthAwarePaginator
    {
        return $this->repository->paginate(
            page: $query->page,
            perPage: $query->perPage
        );
    }
}
