<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    /** @use HasFactory<\Database\Factories\DeckFactory> */
    use HasFactory, HasUlids;

    protected $table = 'decks';

    protected $fillable = [
        'locale',
        'name',
        'type',
        'created_by'
    ];
}
