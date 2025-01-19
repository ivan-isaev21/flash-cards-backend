<?php

namespace App\Infrastructure\Repositories;

use App\Application\Cards\Enums\DeckType;
use App\Application\Cards\ValueObjects\DeckId;
use App\Application\Shared\Enums\Locale;
use App\Application\User\ValueObjects\UserId;
use App\Domain\Cards\Entities\Deck;
use App\Domain\Cards\Repositories\DeckRepository;
use App\Models\Deck as DeckModel;
use DateTimeImmutable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EloquentDeckRepository implements DeckRepository
{
    public function paginate(int $page, int $perPage): LengthAwarePaginator
    {
        $paginator = DeckModel::query()->paginate($perPage, ['*'], 'page', $page);

        $entities = $paginator->getCollection()->map(function ($model) {
            return $this->mapToEntity($model);
        });

        $paginator->setCollection($entities);

        return $paginator;
    }

    public function findDeckById(DeckId $id): ?Deck
    {
        $deckModel = DeckModel::find($id);
        return $deckModel !== null ? $this->mapToEntity($deckModel) : null;
    }

    public function findDeckByName(string $name): ?Deck
    {
        $deckModel = DeckModel::where('name', $name)->first();
        return $deckModel !== null ? $this->mapToEntity($deckModel) : null;
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

            return $this->mapToEntity($deckModel);
        });
    }

    public function update(Deck $deck): Deck
    {
        return DB::transaction(function () use ($deck) {
            $deckModel = DeckModel::findOrFail($deck->id);
            $deckModel->update([
                'id' => $deck->id,
                'locale' => $deck->locale->value,
                'name' => $deck->name,
                'type' => $deck->type->value,
                'created_by' => $deck->createdBy
            ]);
            return $this->mapToEntity($deckModel);
        });
    }

    public function delete(DeckId $id): void
    {
        DB::transaction(function () use ($id) {
            $deckModel = DeckModel::findOrFail($id);
            $deckModel->delete();
        });
    }

    private function mapToEntity(DeckModel $deckModel): Deck
    {
        return new Deck(
            id: new DeckId($deckModel->id),
            locale: Locale::from($deckModel->locale),
            name: $deckModel->name,
            type: DeckType::from($deckModel->type),
            createdBy: new UserId($deckModel->created_by),
            createdAt: new DateTimeImmutable($deckModel->created_at),
            updatedAt: new DateTimeImmutable($deckModel->updated_at),
        );
    }
}
