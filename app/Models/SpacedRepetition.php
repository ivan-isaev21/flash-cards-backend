<?php

namespace App\Models;

use App\Application\Cards\ValueObjects\SpacedRepetitionId;
use App\Application\User\ValueObjects\UserId;
use App\Domain\Cards\Entities\SpacedRepetition as SpacedRepetitionEntity;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpacedRepetition extends Model
{
    /** @use HasFactory<\Database\Factories\SpacedRepetitionFactory> */
    use HasFactory;

    protected $table = 'spaced_repetitions';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'deck_item_id',
        'easiness',
        'repetition',
        'interval',
        'next_date'
    ];

    public function deckItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DeckItem::class);
    }

    public function mapToEntity(): SpacedRepetitionEntity
    {
        return new SpacedRepetitionEntity(
            id: new SpacedRepetitionId($this->id),
            userId: new UserId($this->user_id),
            deckItem: $this->deckItem->mapToEntity(),
            easiness: $this->easiness,
            repetition: $this->repetition,
            interval: $this->interval,
            nextDate: $this->next_date !== null ? new DateTimeImmutable($this->next_date) : null,
            createdAt: new DateTimeImmutable($this->created_at),
            updatedAt: new DateTimeImmutable($this->updated_at),
        );
    }
}
