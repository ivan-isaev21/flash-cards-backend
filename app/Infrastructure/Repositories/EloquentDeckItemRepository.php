<?php

namespace App\Infrastructure\Repositories;

use App\Application\Cards\ValueObjects\DeckId;
use App\Application\Cards\ValueObjects\DeckItemId;
use App\Domain\Cards\Entities\DeckItem;
use App\Domain\Cards\Repositories\DeckItemRepository;
use App\Models\DeckItem as DeckItemModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EloquentDeckItemRepository implements DeckItemRepository
{
    public function paginate(DeckId $deckId, int $page, int $perPage): LengthAwarePaginator
    {
        $paginator = DeckItemModel::query()->where(['deck_id' => $deckId])->paginate($perPage, ['*'], 'page', $page);

        $entities = $paginator->getCollection()->map(function ($model) {
            return $model->mapToEntity();
        });

        $paginator->setCollection($entities);

        return $paginator;
    }

    public function findDeckItemById(DeckItemId $id): ?DeckItem
    {
        $deckItemModel = DeckItemModel::find($id);
        return $deckItemModel !== null ? $deckItemModel->mapToEntity() : null;
    }

    public function create(DeckItem $deckItem): DeckItem
    {
        return DB::transaction(function () use ($deckItem) {
            $deckItemModel = DeckItemModel::create([
                'id' => $deckItem->id->getValue(),
                'deck_id' => $deckItem->deck->id->getValue(),
                'card_id' => $deckItem->card->id->getValue()
            ]);

            return $deckItemModel->mapToEntity();
        });
    }

    public function update(DeckItem $deckItem): DeckItem
    {
        return DB::transaction(function () use ($deckItem) {
            $deckItemModel = DeckItemModel::findOrFail($deckItem->id->getValue());
            $deckItemModel->update([
                'deck_id' => $deckItem->deck->id->getValue(),
                'card_id' => $deckItem->card->id->getValue(),
            ]);

            return $deckItemModel->mapToEntity();
        });
    }

    public function delete(DeckItemId $id): void
    {
        DB::transaction(function () use ($id) {
            $deckItemModel = DeckItemModel::findOrFail($id);
            $deckItemModel->delete();
        });
    }
}
