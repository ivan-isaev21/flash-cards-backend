<?php

namespace App\Infrastructure\Repositories;

use App\Application\Cards\ValueObjects\DeckId;
use App\Domain\Cards\Entities\Deck;
use App\Domain\Cards\Repositories\DeckRepository;
use App\Models\Deck as DeckModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EloquentDeckRepository implements DeckRepository
{
    public function paginate(int $page, int $perPage): LengthAwarePaginator
    {
        $paginator = DeckModel::query()->paginate($perPage, ['*'], 'page', $page);

        $entities = $paginator->getCollection()->map(function ($model) {
            return $model->mapToEntity();
        });

        $paginator->setCollection($entities);

        return $paginator;
    }

    public function findDeckById(DeckId $id): ?Deck
    {
        $deckModel = DeckModel::find($id);
        return $deckModel !== null ? $deckModel->mapToEntity() : null;
    }

    public function findDeckByName(string $name): ?Deck
    {
        $deckModel = DeckModel::where('name', $name)->first();
        return $deckModel !== null ? $deckModel->mapToEntity() : null;
    }

    public function create(Deck $deck): Deck
    {
        return DB::transaction(function () use ($deck) {
            $deckModel = DeckModel::create([
                'id' => $deck->id->getValue(),
                'locale' => $deck->locale->value,
                'name' => $deck->name,
                'type' => $deck->type->value,
                'created_by' => $deck->createdBy
            ]);

            return $deckModel->mapToEntity();
        });
    }

    public function update(Deck $deck): Deck
    {
        return DB::transaction(function () use ($deck) {
            $deckModel = DeckModel::findOrFail($deck->id->getValue());
            $deckModel->update([
                'id' => $deck->id,
                'locale' => $deck->locale->value,
                'name' => $deck->name,
                'type' => $deck->type->value,
                'created_by' => $deck->createdBy
            ]);
            return $deckModel->mapToEntity();
        });
    }

    public function delete(DeckId $id): void
    {
        DB::transaction(function () use ($id) {
            $deckModel = DeckModel::findOrFail($id);
            $deckModel->delete();
        });
    }
}
