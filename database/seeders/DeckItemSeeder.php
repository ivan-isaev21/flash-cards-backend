<?php

namespace Database\Seeders;

use App\Application\Shared\Enums\Locale;
use App\Models\Card;
use App\Models\Deck;
use App\Models\DeckItem;
use Database\Factories\DeckItemFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DeckItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $decks = Deck::factory(10)->create(['locale' => Locale::RU_RU->value]);
        $cards = Card::factory(100)->create(['locale' => Locale::RU_RU->value]);

        foreach ($decks as $deck) {
            foreach (fake()->randomElements($cards, rand(5, 15)) as $card) {
                $deckItem =  DeckItem::factory()->create([
                    'deck_id' => $deck->id,
                    'card_id' => $card->id
                ]);
            }
        }
    }
}
