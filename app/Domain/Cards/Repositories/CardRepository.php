<?php

namespace App\Domain\Cards\Repositories;

use App\Application\Cards\ValueObjects\CardId;
use App\Domain\Cards\Entities\Card;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CardRepository
{
    public function paginate(int $page, int $perPage): LengthAwarePaginator;
    public function create(Card $card): Card;
    public function findCardById(CardId $id): ?Card;
    public function update(Card $card): Card;
    public function delete(CardId $id): void;
}
