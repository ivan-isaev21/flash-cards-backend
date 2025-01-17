<?php

namespace Tests\Unit\Domain\Cards\Services;

use App\Application\Cards\Commands\CreateDeckItemCommand;
use App\Application\Cards\Commands\DeleteDeckItemCommand;
use App\Application\Cards\Commands\DublicateDeckItemCommand;
use App\Application\Cards\Commands\UpdateDeckItemCommand;
use App\Application\Cards\Enums\DeckItemType;
use App\Application\Cards\Enums\DeckType;
use App\Application\Cards\Handlers\CreateDeckItemHandler;
use App\Application\Cards\Handlers\DeleteDeckItemHandler;
use App\Application\Cards\Handlers\GetDeckItemsHandler;
use App\Application\Cards\Handlers\UpdateDeckItemHandler;
use App\Application\Cards\Queries\GetDeckItemsQuery;
use App\Application\Cards\ValueObjects\CardId;
use App\Application\Cards\ValueObjects\DeckId;
use App\Application\Cards\ValueObjects\DeckItemId;
use App\Application\Shared\Enums\Locale;
use App\Application\User\ValueObjects\UserId;
use App\Domain\Cards\Entities\Card;
use App\Domain\Cards\Entities\Deck;
use App\Domain\Cards\Entities\DeckItem;
use App\Domain\Cards\Events\DeckItemCreated;
use App\Domain\Cards\Events\DeckItemDeleted;
use App\Domain\Cards\Events\DeckItemUpdated;
use App\Domain\Cards\Exceptions\CardNotFoundException;
use App\Domain\Cards\Exceptions\DeckItemNotFoundException;
use App\Domain\Cards\Exceptions\DeckNotFoundException;
use App\Domain\Cards\Services\DeckItemService;
use DateTimeImmutable;
use Mockery;
use PHPUnit\Framework\TestCase;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DeckItemServiceTest extends TestCase
{
    private Dispatcher $dispatcher;
    private GetDeckItemsHandler $getDeckItemsHandler;
    private CreateDeckItemHandler $createDeckItemHandler;
    private UpdateDeckItemHandler $updateDeckItemHandler;
    private DeleteDeckItemHandler $deleteDeckItemHandler;
    private DeckItemService $deckService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dispatcher = Mockery::mock(Dispatcher::class);
        $this->getDeckItemsHandler = Mockery::mock(GetDeckItemsHandler::class);
        $this->createDeckItemHandler = Mockery::mock(CreateDeckItemHandler::class);
        $this->updateDeckItemHandler = Mockery::mock(UpdateDeckItemHandler::class);
        $this->deleteDeckItemHandler = Mockery::mock(DeleteDeckItemHandler::class);

        $this->deckService = new DeckItemService(
            dispatcher: $this->dispatcher,
            getDeckItemsHandler: $this->getDeckItemsHandler,
            createDeckItemHandler: $this->createDeckItemHandler,
            updateDeckItemHandler: $this->updateDeckItemHandler,
            deleteDeckItemHandler: $this->deleteDeckItemHandler
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

    private function buildDeck(): Deck
    {
        $id = DeckId::next();
        $name = fake()->name();
        $locale = Locale::EN_US;
        $type = DeckType::PRIVATE;
        $createdBy = UserId::next();
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTimeImmutable();

        return new Deck(
            id: $id,
            name: $name,
            locale:$locale,
            type: $type,
            createdBy: $createdBy,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }

    private function buildDeckItem(): DeckItem
    {
        $id = DeckItemId::next();
        $deck = $this->buildDeck();
        $card = $this->buildCard();
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTimeImmutable();

        return new DeckItem(
            id: $id,
            deck: $deck,
            card: $card,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }

    public function test_paginate(): void
    {
        $page = 1;
        $perPage = 10;

        $query = new GetDeckItemsQuery(deckId: DeckId::next(),  page: $page, perPage: $perPage);

        $paginatedResult = Mockery::mock(LengthAwarePaginator::class);

        $this->getDeckItemsHandler
            ->shouldReceive('handle')
            ->once()
            ->with($query)
            ->andReturn($paginatedResult);

        $result = $this->deckService->paginate($query);

        $this->assertEquals($paginatedResult, $result);
    }

    public function test_success_create(): void
    {
        $deckItem = $this->buildDeckItem();

        $command = new CreateDeckItemCommand(
            deckId: $deckItem->deck->id,
            cardId: $deckItem->card->id,
        );

        $this->createDeckItemHandler
            ->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andReturn($deckItem);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(DeckItemCreated::class));

        $result = $this->deckService->create($command);

        $this->assertEquals($deckItem, $result);
    }

    public function test_success_update(): void
    {
        $deckItem = $this->buildDeckItem();

        $command = new UpdateDeckItemCommand(
            id: $deckItem->id,
            deckId: $deckItem->deck->id,
            cardId: $deckItem->card->id,
        );

        $this->updateDeckItemHandler
            ->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andReturn($deckItem);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(DeckItemUpdated::class));

        $result = $this->deckService->update($command);

        $this->assertEquals($deckItem, $result);
    }

    public function test_not_found_deck_item_update(): void
    {
        $notFoundId = DeckItemId::next();

        $command = new UpdateDeckItemCommand(
            id: $notFoundId,
            deckId: DeckId::next(),
            cardId: CardId::next()
        );

        $this->updateDeckItemHandler
            ->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andThrow(new DeckItemNotFoundException($notFoundId));

        $this->expectException(DeckItemNotFoundException::class);
        $this->deckService->update($command);
    }

    public function test_not_found_deck_update(): void
    {
        $notFoundId = Deckid::next();

        $command = new UpdateDeckItemCommand(
            id: DeckItemId::next(),
            deckId: $notFoundId,
            cardId: CardId::next()
        );

        $this->updateDeckItemHandler
            ->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andThrow(new DeckNotFoundException($notFoundId));

        $this->expectException(DeckNotFoundException::class);
        $this->deckService->update($command);
    }

    public function test_not_found_card_update(): void
    {
        $notFoundId = CardId::next();

        $command = new UpdateDeckItemCommand(
            id: DeckItemId::next(),
            deckId: DeckId::next(),
            cardId: $notFoundId
        );

        $this->updateDeckItemHandler
            ->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andThrow(new CardNotFoundException($notFoundId));

        $this->expectException(CardNotFoundException::class);
        $this->deckService->update($command);
    }

    public function test_success_delete(): void
    {
        $deckItem = $this->buildDeckItem();

        $command = new DeleteDeckItemCommand(
            id: $deckItem->id
        );

        $this->deleteDeckItemHandler
            ->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andReturn();

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(DeckItemDeleted::class));

        $this->deckService->delete($command);

        $this->assertTrue(true);
    }
}
