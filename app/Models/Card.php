<?php

namespace App\Models;

use App\Application\Cards\ValueObjects\CardId;
use App\Application\Shared\Enums\Locale;
use App\Application\User\ValueObjects\UserId;
use App\Domain\Cards\Entities\Card as CardEntity;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    /** @use HasFactory<\Database\Factories\CardFactory> */
    use HasFactory;

    protected $table = 'cards';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'locale',
        'question',
        'answer',
        'keywords',
        'created_by'
    ];

    protected $casts = [
        'keywords' => 'array'
    ];

    public function mapToEntity(): CardEntity
    {
        return new CardEntity(
            id: new CardId($this->id),
            locale: Locale::from($this->locale),
            question: $this->question,
            answer: $this->answer,
            keywords: $this->keywords,
            createdBy: new UserId($this->created_by),
            createdAt: new DateTimeImmutable($this->created_at),
            updatedAt: new DateTimeImmutable($this->updated_at)
        );
    }
}
