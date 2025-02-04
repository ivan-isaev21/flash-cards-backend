<?php

namespace Tests\Feature\Api\v1;

use App\Models\Card;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class CardControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $returnStructure;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->returnStructure = [
            'id',
            'locale',
            'question',
            'answer',
            'keywords',
            'createdBy',
            'createdAt',
            'updatedAt'
        ];

        $this->user = User::factory()->withVerifiedToken()->create();
    }

    public function test_can_paginate_cards()
    {
        $cards = Card::factory()->count(10)->create();
        $response = $this->getJson(route('api.v1.cards.paginate'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => $this->returnStructure
            ],
            'links',
            'meta'
        ]);

        $response->assertJsonFragment([
            'id' => $cards->first()->id,
            'question' => $cards->first()->question,
        ]);
    }

    public function test_can_create_a_card()
    {
        $fakeData = Card::factory()->make();

        $cardData = [
            'locale' => $fakeData->locale,
            'question' => $fakeData->question,
            'answer' => $fakeData->answer,
            'keywords' => $fakeData->keywords
        ];

        $response = $this->actingAs($this->user)->postJson(route('api.v1.cards.create'),  $cardData);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure($this->returnStructure);
    }

    public function test_can_update_card()
    {
        $card = Card::factory()->create();

        $updateData = [
            'question' => 'Updated question',
            'answer' => 'Updated answer',
        ];

        $response = $this->actingAs($this->user)->putJson(route('api.v1.cards.update', ['id' => $card->id]), $updateData);
        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJsonStructure($this->returnStructure);
    }

    public function test_can_delete_card()
    {
        $card = Card::factory()->create();
        $response = $this->actingAs($this->user)->deleteJson(route('api.v1.cards.delete', ['id' => $card->id]));
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
