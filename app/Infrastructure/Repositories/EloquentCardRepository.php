<?php

namespace App\Infrastructure\Repositories;

use App\Application\Cards\ValueObjects\CardId;
use App\Application\Shared\Enums\Locale;
use App\Domain\Cards\Entities\Card;
use App\Domain\Cards\Repositories\CardRepository;
use App\Models\Card as CardModel;
use DateTimeImmutable;
use Illuminate\Container\Attributes\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentCardRepository implements CardRepository
{
    public function paginate(int $page, int $perPage): LengthAwarePaginator
    {
        $paginator = CardModel::query()->paginate($perPage, ['*'], 'page', $page);

        $entities = $paginator->getCollection()->map(function ($model) {
            return $this->mapToEntity($model);
        });

        $paginator->setCollection($entities);

        return $paginator;
    }

    public function findCardById(CardId $id): ?Card
    {
        $cardModel = CardModel::find($id);
        return $cardModel !== null ? $this->mapToEntity($cardModel) : null;
    }

    public function create(Card $card): Card
    {
        return DB::transaction(function () use ($card) {
            $cardModel = CardModel::create([
                'id' => $card->id,
                'locale' => $card->locale->value,
                'question' => $card->question,
                'answer' => $card->answer,
                'keywords' => $card->answer,
                'created_by' => $card->createdBy
            ]);

            return $this->mapToEntity($cardModel);
        });
    }

    public function update(Card $card): Card
    {
        return DB::transaction(function () use ($card) {
            $cardModel = CardModel::findOrFail($card->id);
            $cardModel->update([
                'locale' => $card->locale->value,
                'question' => $card->question,
                'answer' => $card->answer,
                'keywords' => $card->answer,
            ]);
            return $this->mapToEntity($cardModel);
        });
    }

    public function delete(CardId $id): void
    {
        DB::transaction(function () use ($id) {
            $cardModel = CardModel::findOrFail($id);
            $cardModel->delete();
        });
    }

    private function mapToEntity(CardModel $card): Card
    {
        return new Card(
            id: $card->id,
            locale: Locale::from($card->locale),
            question: $card->question,
            answer: $card->answer,
            keywords: $card->keywords,
            createdBy: $card->created_by,
            createdAt: new DateTimeImmutable($card->created_at),
            updatedAt: new DateTimeImmutable($card->updated_at)
        );
    }
}
