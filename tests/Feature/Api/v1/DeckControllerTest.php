<?php

namespace Tests\Feature\Api\v1;

use App\Application\Cards\Enums\DeckType;
use App\Application\Shared\Enums\Locale;
use App\Models\Deck;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class DeckControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $returnStructure;

    protected function setUp(): void
    {
        parent::setUp();

        $this->returnStructure = [
            'id',
            'name',
            'locale',
            'type',
            'createdBy',
            'createdAt',
            'updatedAt'
        ];
    }

    public function test_can_paginate_decks()
    {
        $decks = Deck::factory()->count(10)->create();
        $response = $this->getJson(route('api.v1.decks.paginate'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => $this->returnStructure
            ],
            'links',
            'meta'
        ]);

        $response->assertJsonFragment([
            'id' => $decks->first()->id,
            'locale' => $decks->first()->locale,
            'name' => $decks->first()->name,
        ]);
    }

    public function test_can_create_a_deck()
    {
        $fakeData = Deck::factory()->make();

        $deckData = [
            'locale' => $fakeData->locale,
            'type' => $fakeData->type,
            'name' => $fakeData->name,
        ];

        $response = $this->postJson(route('api.v1.decks.create'),  $deckData);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure($this->returnStructure);
    }

    public function test_can_update_deck()
    {
        $deck = Deck::factory()->create();

        $updateData = [
            'locale' => fake()->randomElement(Locale::cases())->value,
            'name' => 'Updated deck name'
        ];

        $response = $this->putJson(route('api.v1.decks.update', ['id' => $deck->id]), $updateData);
        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJsonStructure($this->returnStructure);
    }

    public function test_can_delete_deck()
    {
        $deck = Deck::factory()->create();
        $response = $this->deleteJson(route('api.v1.decks.delete', ['id' => $deck->id]));
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
