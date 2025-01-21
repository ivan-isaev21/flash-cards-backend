<?php

namespace App\Domain\Cards\Repositories;

use App\Application\Cards\ValueObjects\DeckId;
use App\Application\Cards\ValueObjects\DeckItemId;
use App\Domain\Cards\Entities\DeckItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DeckItemRepository
{
    public function paginate(DeckId $deckId, int $page, int $perPage): LengthAwarePaginator;
    public function create(DeckItem $deckItem): DeckItem;
    public function findDeckItemById(DeckItemId $id): ?DeckItem;
    public function update(DeckItem $deckItem): DeckItem;
    public function delete(DeckItemId $id): void;
}
