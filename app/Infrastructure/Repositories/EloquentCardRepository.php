<?php

namespace App\Infrastructure\Repositories;

use App\Application\Cards\ValueObjects\CardId;
use App\Domain\Cards\Entities\Card;
use App\Domain\Cards\Repositories\CardRepository;
use App\Models\Card as CardModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EloquentCardRepository implements CardRepository
{
    public function paginate(int $page, int $perPage): LengthAwarePaginator
    {
        $paginator = CardModel::query()->paginate($perPage, ['*'], 'page', $page);

        $entities = $paginator->getCollection()->map(function ($model) {
            return $model->mapToEntity();
        });

        $paginator->setCollection($entities);

        return $paginator;
    }

    public function findCardById(CardId $id): ?Card
    {
        $cardModel = CardModel::find($id);
        return $cardModel !== null ? $cardModel->mapToEntity() : null;
    }

    public function create(Card $card): Card
    {
        return DB::transaction(function () use ($card) {
            $cardModel = CardModel::create([
                'id' => $card->id->getValue(),
                'locale' => $card->locale->value,
                'question' => $card->question,
                'answer' => $card->answer,
                'keywords' => $card->keywords,
                'created_by' => $card->createdBy
            ]);

            return $cardModel->mapToEntity();
        });
    }

    public function update(Card $card): Card
    {
        return DB::transaction(function () use ($card) {
            $cardModel = CardModel::findOrFail($card->id->getValue());
            $cardModel->update([
                'locale' => $card->locale->value,
                'question' => $card->question,
                'answer' => $card->answer,
                'keywords' => $card->keywords,
            ]);
            return $cardModel->mapToEntity();
        });
    }

    public function delete(CardId $id): void
    {
        DB::transaction(function () use ($id) {
            $cardModel = CardModel::findOrFail($id);
            $cardModel->delete();
        });
    }
}
