<?php

namespace App\Models;

use App\Application\Cards\Enums\DeckType;
use App\Application\Cards\ValueObjects\DeckId;
use App\Application\Shared\Enums\Locale;
use App\Application\User\ValueObjects\UserId;
use App\Domain\Cards\Entities\Deck as DeckEntity;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    /** @use HasFactory<\Database\Factories\DeckFactory> */
    use HasFactory;

    protected $table = 'decks';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'locale',
        'name',
        'type',
        'created_by'
    ];

    public function mapToEntity(): DeckEntity
    {
        return new DeckEntity(
            id: new DeckId($this->id),
            locale: Locale::from($this->locale),
            name: $this->name,
            type: DeckType::from($this->type),
            createdBy: new UserId($this->created_by),
            createdAt: new DateTimeImmutable($this->created_at),
            updatedAt: new DateTimeImmutable($this->updated_at),
        );
    }
}
