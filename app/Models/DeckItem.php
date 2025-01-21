<?php

namespace App\Models;

use App\Application\Cards\ValueObjects\DeckItemId;
use App\Domain\Cards\Entities\DeckItem as DeckItemEntity;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeckItem extends Model
{
    /** @use HasFactory<\Database\Factories\DeckItemFactory> */
    use HasFactory;

    protected $table = 'deck_items';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'deck_id',
        'card_id'
    ];

    public function deck(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Deck::class);
    }

    public function card(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function mapToEntity(): DeckItemEntity
    {
        return new DeckItemEntity(
            id: new DeckItemId($this->id),
            deck: $this->deck->mapToEntity(),
            card: $this->card->mapToEntity(),
            createdAt: new DateTimeImmutable($this->created_at),
            updatedAt: new DateTimeImmutable($this->updated_at),
        );
    }
}
