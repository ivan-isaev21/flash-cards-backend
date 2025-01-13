<?php

namespace App\Domain\Cards\Repositories;

use App\Application\Cards\ValueObjects\DeckId;
use App\Domain\Cards\Entities\Deck;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DeckRepository
{
    public function paginate(int $page, int $perPage): LengthAwarePaginator;
    public function create(Deck $deck): Deck;
    public function findDeckById(DeckId $id): ?Deck;
    public function update(Deck $deck): Deck;
    public function delete(DeckId $id): void;
}
