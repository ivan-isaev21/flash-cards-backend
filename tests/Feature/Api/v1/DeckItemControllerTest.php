<?php

namespace Tests\Feature\Api\v1;

use App\Application\Cards\Enums\DeckType;
use App\Application\Shared\Enums\Locale;
use App\Models\Card;
use App\Models\Deck;
use App\Models\DeckItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class DeckItemControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $returnStructure;
    protected DeckItem $deckItem;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->returnStructure = [
            'id',
            'deck',
            'card',
            'createdAt',
            'updatedAt'
        ];

        $deck = Deck::factory()->create();
        $card = Card::factory()->create();
        $this->deckItem = DeckItem::factory()->create(
            [
                'deck_id' => $deck->id,
                'card_id' => $card->id
            ]
        );

        $this->user = User::factory()->withVerifiedToken()->create();
    }

    public function test_can_paginate_deck_items()
    {
        $response = $this->getJson(route('api.v1.deck-items.paginate', ['deckId' => $this->deckItem->deck_id]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => $this->returnStructure
            ],
            'links',
            'meta'
        ]);

        $response->assertJsonFragment([
            'id' => $this->deckItem->id,
        ]);
    }

    public function test_can_create_a_deck_item()
    {
        $deck = Deck::factory()->create();
        $card = Card::factory()->create();

        $deckItemData = [
            'deckId' => $deck->id,
            'cardId' => $card->id
        ];

        $response = $this->actingAs($this->user)->postJson(route('api.v1.deck-items.create'),  $deckItemData);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure($this->returnStructure);
    }

    public function test_can_update_deck_item()
    {
        $deck = Deck::factory()->create();
        $card = Card::factory()->create();

        $updateData = [
            'deckId' => $deck->id,
            'cardId' => $card->id
        ];

        $response = $this->actingAs($this->user)->putJson(route('api.v1.deck-items.update', ['id' => $this->deckItem->id]), $updateData);
        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJsonStructure($this->returnStructure);
    }

    public function test_can_delete_deck_item()
    {
        $response = $this->actingAs($this->user)->deleteJson(route('api.v1.deck-items.delete', ['id' => $this->deckItem->id]));
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
