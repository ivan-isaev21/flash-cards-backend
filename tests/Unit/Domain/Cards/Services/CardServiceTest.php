<?php

namespace Tests\Unit\Domain\Cards\Services;

use App\Application\Cards\Commands\CreateCardCommand;
use App\Application\Cards\Commands\DeleteCardCommand;
use App\Application\Cards\Commands\DublicateCardCommand;
use App\Application\Cards\Commands\UpdateCardCommand;
use App\Application\Cards\Handlers\CreateCardHandler;
use App\Application\Cards\Handlers\DeleteCardHandler;
use App\Application\Cards\Handlers\DublicateCardHandler;
use App\Application\Cards\Handlers\GetCardsHandler;
use App\Application\Cards\Handlers\UpdateCardHandler;
use App\Application\Cards\Queries\GetCardsQuery;
use App\Application\Cards\ValueObjects\CardId;
use App\Application\Shared\Enums\Locale;
use App\Application\User\ValueObjects\UserId;
use App\Domain\Cards\Entities\Card;
use App\Domain\Cards\Events\CardCreated;
use App\Domain\Cards\Events\CardDeleted;
use App\Domain\Cards\Events\CardDublicated;
use App\Domain\Cards\Events\CardUpdated;
use App\Domain\Cards\Exceptions\CardNotFoundException;
use App\Domain\Cards\Services\CardService;
use DateTimeImmutable;
use Mockery;
use PHPUnit\Framework\TestCase;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CardServiceTest extends TestCase
{
    private Dispatcher $dispatcher;
    private GetCardsHandler $getCardsHandler;
    private CreateCardHandler $createCardHandler;
    private UpdateCardHandler $updateCardHandler;
    private DublicateCardHandler $dublicateCardHandler;
    private DeleteCardHandler $deleteCardHandler;

    private CardService $cardService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dispatcher = Mockery::mock(Dispatcher::class);
        $this->getCardsHandler = Mockery::mock(GetCardsHandler::class);
        $this->createCardHandler = Mockery::mock(CreateCardHandler::class);
        $this->updateCardHandler = Mockery::mock(UpdateCardHandler::class);
        $this->dublicateCardHandler = Mockery::mock(DublicateCardHandler::class);
        $this->deleteCardHandler = Mockery::mock(DeleteCardHandler::class);

        $this->cardService = new CardService(
            dispatcher: $this->dispatcher,
            getCardsHandler: $this->getCardsHandler,
            createCardHandler: $this->createCardHandler,
            updateCardHandler: $this->updateCardHandler,
            dublicateCardHandler: $this->dublicateCardHandler,
            deleteCardHandler: $this->deleteCardHandler
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function buildCard(): Card
    {
        $id = CardId::next();
        $locale = Locale::EN_US;
        $question = 'question?';
        $answer = 'answer';
        $keywords = ['keyword1', 'keyword2'];
        $createdBy = UserId::next();
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTimeImmutable();

        return new Card(
            id: $id,
            locale: $locale,
            question: $question,
            answer: $answer,
            keywords: $keywords,
            createdBy: $createdBy,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }

    public function test_paginate(): void
    {
        $page = 1;
        $perPage = 10;

        $query = new GetCardsQuery(page: $page, perPage: $perPage);

        $paginatedResult = Mockery::mock(LengthAwarePaginator::class);

        $this->getCardsHandler
            ->shouldReceive('handle')
            ->once()
            ->with($query)
            ->andReturn($paginatedResult);

        $result = $this->cardService->paginate($query);

        $this->assertEquals($paginatedResult, $result);
    }

    public function test_success_create(): void
    {
        $card = $this->buildCard();

        $command = new CreateCardCommand(
            locale: $card->locale,
            question: $card->question,
            answer: $card->answer,
            keywords: $card->keywords,
            createdBy: $card->createdBy
        );

        $this->createCardHandler
            ->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andReturn($card);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(CardCreated::class));

        $result = $this->cardService->create($command);

        $this->assertEquals($card, $result);
    }

    public function test_success_update(): void
    {
        $card = $this->buildCard();

        $command = new UpdateCardCommand(
            id: $card->id,
            locale: null,
            question: null,
            answer: null,
            keywords: null,
            createdBy: $card->createdBy
        );

        $this->updateCardHandler
            ->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andReturn($card);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(CardUpdated::class));

        $result = $this->cardService->update($command);

        $this->assertEquals($card, $result);
    }

    public function test_not_found_update(): void
    {
        $notFoundId = CardId::next();

        $command = new UpdateCardCommand(
            id: $notFoundId,
            locale: null,
            question: null,
            answer: null,
            keywords: null,
            createdBy: UserId::next()
        );

        $this->updateCardHandler
            ->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andThrow(new CardNotFoundException($notFoundId));

        $this->expectException(CardNotFoundException::class);
        $this->cardService->update($command);
    }

    public function test_success_dublicate(): void
    {
        $card = $this->buildCard();
        $dublicatedCard = $this->buildCard();

        $command = new DublicateCardCommand(
            id: $card->id,
            createdBy: UserId::next()
        );

        $this->dublicateCardHandler
            ->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andReturn($dublicatedCard);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(CardDublicated::class));

        $result = $this->cardService->dublicate($command);

        $this->assertNotTrue($card->id->equals($result->id));
        $this->assertEquals($card->locale, $dublicatedCard->locale);
        $this->assertEquals($card->question, $dublicatedCard->question);
        $this->assertEquals($card->answer, $dublicatedCard->answer);
        $this->assertEquals($card->keywords, $dublicatedCard->keywords);
        $this->assertNotTrue($card->createdBy->equals($result->createdBy));
        $this->assertNotEquals($card->createdAt, $dublicatedCard->createdAt);
        $this->assertNotEquals($card->updatedAt, $dublicatedCard->updatedAt);
    }

    public function test_not_found_dublicate(): void
    {
        $notFoundId = CardId::next();

        $command = new DublicateCardCommand(
            id: $notFoundId,
            createdBy: UserId::next()
        );

        $this->dublicateCardHandler
            ->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andThrow(new CardNotFoundException($notFoundId));

        $this->expectException(CardNotFoundException::class);
        $this->cardService->dublicate($command);
    }

    public function test_success_delete(): void
    {
        $card = $this->buildCard();

        $command = new DeleteCardCommand(
            id: $card->id
        );

        $this->deleteCardHandler
            ->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andReturn();

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(CardDeleted::class));

        $this->cardService->delete($command);

        $this->assertTrue(true);
    }
}
