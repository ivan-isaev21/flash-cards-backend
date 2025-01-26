<?php
namespace Tests\Feature\Api\v1;

use App\Models\Card;
use App\Models\Deck;
use App\Models\DeckItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class StudyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_card_to_review()
    {
        $deck = Deck::factory()->create();
        $card = Card::factory()->create();
        DeckItem::factory()->create(['deck_id' => $deck, 'card_id' => $card->id]);
        $response = $this->getJson(route('api.v1.study.getCardToReview', ['deckId' => $deck->id]));
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_submit_review()
    {
        $deck = Deck::factory()->create();
        $card = Card::factory()->create();
        $deckItem = DeckItem::factory()->create(['deck_id' => $deck, 'card_id' => $card->id]);
        $response = $this->putJson(route('api.v1.study.submitReview', ['deckItemId' => $deckItem->id]), ['quality' => 3]);
        $response->assertStatus(Response::HTTP_ACCEPTED);
    }
}
