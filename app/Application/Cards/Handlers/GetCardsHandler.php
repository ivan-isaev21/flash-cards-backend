<?php

namespace App\Application\Cards\Handlers;

use App\Application\Cards\Queries\GetCardsQuery;
use App\Domain\Cards\Repositories\CardRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetCardsHandler
{
    private CardRepository $repository;

    public function __construct(CardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetCardsQuery $query): LengthAwarePaginator
    {
        return $this->repository->paginate(
            page: $query->page,
            perPage: $query->perPage
        );
    }
}
