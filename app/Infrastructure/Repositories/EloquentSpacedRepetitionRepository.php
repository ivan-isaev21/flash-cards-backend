<?php

namespace App\Infrastructure\Repositories;

use App\Application\Cards\ValueObjects\DeckId;
use App\Application\Cards\ValueObjects\DeckItemId;
use App\Application\Cards\ValueObjects\SpacedRepetitionId;
use App\Application\User\ValueObjects\UserId;
use App\Domain\Cards\Entities\DeckItem;
use App\Domain\Cards\Entities\SpacedRepetition;
use App\Domain\Cards\Repositories\SpacedRepetitionRepository;
use App\Models\SpacedRepetition as SpacedRepetitionModel;

use Illuminate\Support\Facades\DB;

class EloquentSpacedRepetitionRepository implements SpacedRepetitionRepository
{
    public function findFirstUnstudiedSpacedRepetition(DeckId $deckId, UserId $userId): ?SpacedRepetition
    {
        $model = SpacedRepetitionModel::where('user_id', $userId->getValue())
            ->where('next_date', '<=', DB::raw('NOW()'))
            ->whereHas('deckItem', function ($query) use ($deckId) {
                $query->whereHas('deck', function ($query) use ($deckId) {
                    $query->where('id', $deckId->getValue());
                });
            })
            ->first();

        if ($model === null) {
            return null;
        }

        return $model->mapToEntity();
    }

    public function findSpacedRepetitionByDeckIdAndUserId(DeckItemId $deckItemId, UserId $userId): ?SpacedRepetition
    {
        $model = SpacedRepetitionModel::where(['deck_item_id' => $deckItemId->getValue(), 'user_id' => $userId->getValue()])->first();

        if ($model === null) {
            return null;
        }

        return $model->mapToEntity();
    }

    public function save(SpacedRepetition $spacedRepetition): SpacedRepetition
    {
        return DB::transaction(function () use ($spacedRepetition) {
            $spacedRepetitionModel = SpacedRepetitionModel::updateOrCreate(
                [
                    'deck_item_id' => $spacedRepetition->deckItem->id->getValue(),
                    'user_id' => $spacedRepetition->userId->getValue(),
                ],
                [
                    'id' => $spacedRepetition->id->getValue(),
                    'deck_item_id' => $spacedRepetition->deckItem->id->getValue(),
                    'user_id' => $spacedRepetition->userId->getValue(),
                    'easiness' => $spacedRepetition->easiness,
                    'repetition' => $spacedRepetition->repetition,
                    'interval' => $spacedRepetition->interval,
                    'next_date' => $spacedRepetition->nextDate !== null ?  $spacedRepetition->nextDate->format('Y-m-d H:i:s') : null
                ],
            );

            return $spacedRepetitionModel->mapToEntity();
        });
    }

    public function delete(SpacedRepetitionId $id): void
    {
        DB::transaction(function () use ($id) {
            SpacedRepetitionModel::find($id->getValue())->delete();
        });
    }
}
