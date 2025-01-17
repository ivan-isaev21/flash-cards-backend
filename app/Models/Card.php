<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    /** @use HasFactory<\Database\Factories\CardFactory> */
    use HasFactory, HasUlids;

    protected $table = 'cards';

    protected $fillable = [
        'locale',
        'question',
        'answer',
        'keywords',
        'created_by'
    ];

    protected $casts = [
        'keywords' => 'array'
    ];
}
