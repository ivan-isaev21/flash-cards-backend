<?php

namespace Tests\Unit\Domain\Cards\Services;

use App\Application\Cards\Commands\SubmitReviewCommand;
use App\Application\Cards\Enums\DeckType;
use PHPUnit\Framework\TestCase;
use Illuminate\Contracts\Events\Dispatcher;
use App\Application\Cards\Handlers\GetCardToReviewHandler;
use App\Application\Cards\Handlers\SpacedRepetitionHandler;
use App\Application\Cards\Queries\GetCardToReviewQuery;
use App\Application\Cards\ValueObjects\CardId;
use App\Application\Cards\ValueObjects\DeckId;
use App\Application\Cards\ValueObjects\DeckItemId;
use App\Application\Cards\ValueObjects\SpacedRepetitionId;
use App\Application\Shared\Enums\Locale;
use App\Application\User\ValueObjects\UserId;
use App\Domain\Cards\Entities\Card;
use App\Domain\Cards\Entities\Deck;
use App\Domain\Cards\Services\StudyService;
use App\Domain\Cards\Entities\DeckItem;
use App\Domain\Cards\Entities\SpacedRepetition;
use App\Domain\Cards\Events\ReviewSubmited;
use DateTimeImmutable;
use Mockery;

class StudyServiceTest extends TestCase
{
    private Dispatcher $dispatcher;
    private SpacedRepetitionHandler $spacedRepetitionHandler;
    private GetCardToReviewHandler $getCardToReviewHandler;
    private StudyService $studyService;
    private DeckItem $deckItem;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dispatcher = Mockery::mock(Dispatcher::class);
        $this->spacedRepetitionHandler = Mockery::mock(SpacedRepetitionHandler::class);
        $this->getCardToReviewHandler = Mockery::mock(GetCardToReviewHandler::class);
        $this->studyService = new StudyService(
            dispatcher: $this->dispatcher,
            spacedRepetitionHandler: $this->spacedRepetitionHandler,
            getCardToReviewHandler: $this->getCardToReviewHandler
        );
        $this->deckItem = $this->buildDeckItem();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function buildDeckItem(): DeckItem
    {
        $deck = new Deck(
            id: DeckId::next(),
            name: fake()->name(),
            locale: Locale::RU_RU,
            type: DeckType::PUBLIC,
            createdBy: UserId::next()
        );

        $card = new Card(
            id: CardId::next(),
            locale: Locale::RU_RU,
            question: 'question',
            answer: 'answer',
            keywords: ['dfdf', 'ggh'],
            createdBy: UserId::next()
        );

        return new DeckItem(
            id: DeckItemId::next(),
            deck: $deck,
            card: $card,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );
    }

    public function test_get_card_to_review(): void
    {
        $query = new GetCardToReviewQuery($this->deckItem->deck->id, UserId::next());

        $this->getCardToReviewHandler
            ->shouldReceive('handle')
            ->once()
            ->with($query)
            ->andReturn($this->deckItem);

        $result = $this->studyService->getCardToReview($query);

        $this->assertEquals($this->deckItem, $result);
    }

    public function test_submit_review()
    {
        $command = new SubmitReviewCommand($this->deckItem->id, UserId::next(), 3);

        $spacedRepetition = new SpacedRepetition(
            id: SpacedRepetitionId::next(),
            deckItem: $this->deckItem,
            userId: $command->userId,
            easiness: 2.5,
            repetition: 0,
            interval: 0,
            nextDate: null
        );

        $this->spacedRepetitionHandler->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andReturn($spacedRepetition);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(ReviewSubmited::class));

        $result = $this->studyService->submitReview($command);

        $this->assertEquals($result, $spacedRepetition);
    }
}
