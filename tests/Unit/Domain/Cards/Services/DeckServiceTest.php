<?php

namespace Tests\Unit\Domain\Cards\Services;

use App\Application\Cards\Commands\CreateDeckCommand;
use App\Application\Cards\Commands\DeleteDeckCommand;
use App\Application\Cards\Commands\DublicateDeckCommand;
use App\Application\Cards\Commands\UpdateDeckCommand;
use App\Application\Cards\Enums\DeckType;
use App\Application\Cards\Handlers\CreateDeckHandler;
use App\Application\Cards\Handlers\DeleteDeckHandler;
use App\Application\Cards\Handlers\GetDecksHandler;
use App\Application\Cards\Handlers\UpdateDeckHandler;
use App\Application\Cards\Queries\GetDecksQuery;
use App\Application\Cards\ValueObjects\DeckId;
use App\Application\Shared\Enums\Locale;
use App\Application\User\ValueObjects\UserId;
use App\Domain\Cards\Entities\Deck;
use App\Domain\Cards\Events\DeckCreated;
use App\Domain\Cards\Events\DeckDeleted;
use App\Domain\Cards\Events\DeckDublicated;
use App\Domain\Cards\Events\DeckUpdated;
use App\Domain\Cards\Exceptions\DeckNotFoundException;
use App\Domain\Cards\Services\DeckService;
use DateTimeImmutable;
use Mockery;
use PHPUnit\Framework\TestCase;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DeckServiceTest extends TestCase
{
    private Dispatcher $dispatcher;
    private GetDecksHandler $getDecksHandler;
    private CreateDeckHandler $createDeckHandler;
    private UpdateDeckHandler $updateDeckHandler;
    private DeleteDeckHandler $deleteDeckHandler;
    private DeckService $deckService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dispatcher = Mockery::mock(Dispatcher::class);
        $this->getDecksHandler = Mockery::mock(GetDecksHandler::class);
        $this->createDeckHandler = Mockery::mock(CreateDeckHandler::class);
        $this->updateDeckHandler = Mockery::mock(UpdateDeckHandler::class);
        $this->deleteDeckHandler = Mockery::mock(DeleteDeckHandler::class);

        $this->deckService = new DeckService(
            dispatcher: $this->dispatcher,
            getDecksHandler: $this->getDecksHandler,
            createDeckHandler: $this->createDeckHandler,
            updateDeckHandler: $this->updateDeckHandler,
            deleteDeckHandler: $this->deleteDeckHandler
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function buildDeck(): Deck
    {
        $id = DeckId::next();
        $name = fake()->name();
        $type = DeckType::PRIVATE;
        $createdBy = UserId::next();
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTimeImmutable();

        return new Deck(
            id: $id,
            name:$name,
            type: $type,
            createdBy: $createdBy,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }

    public function test_paginate(): void
    {
        $page = 1;
        $perPage = 10;

        $query = new GetDecksQuery(page: $page, perPage: $perPage);

        $paginatedResult = Mockery::mock(LengthAwarePaginator::class);

        $this->getDecksHandler
            ->shouldReceive('handle')
            ->once()
            ->with($query)
            ->andReturn($paginatedResult);

        $result = $this->deckService->paginate($query);

        $this->assertEquals($paginatedResult, $result);
    }

    public function test_success_create(): void
    {
        $deck = $this->buildDeck();

        $command = new CreateDeckCommand(
            name: $deck->name,
            type: $deck->type,
            createdBy: $deck->createdBy
        );

        $this->createDeckHandler
            ->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andReturn($deck);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(DeckCreated::class));

        $result = $this->deckService->create($command);

        $this->assertEquals($deck, $result);
    }

    public function test_success_update(): void
    {
        $deck= $this->buildDeck();

        $command = new UpdateDeckCommand(
            id: $deck->id,
            name: $deck->name,
            type: $deck->type,
            createdBy: $deck->createdBy
        );

        $this->updateDeckHandler
            ->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andReturn($deck);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(DeckUpdated::class));

        $result = $this->deckService->update($command);

        $this->assertEquals($deck, $result);
    }

    public function test_not_found_update(): void
    {
        $notFoundId = DeckId::next();

        $command = new UpdateDeckCommand(
            id: $notFoundId,
            name: 'ghgh',
            type: DeckType::PUBLIC,
            createdBy: UserId::next()
        );

        $this->updateDeckHandler
            ->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andThrow(new DeckNotFoundException());

        $this->expectException(DeckNotFoundException::class);
        $this->deckService->update($command);
    }

    public function test_success_delete(): void
    {
        $deck = $this->buildDeck();

        $command = new DeleteDeckCommand(
            id: $deck->id
        );

        $this->deleteDeckHandler
            ->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andReturn();

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(DeckDeleted::class));

        $this->deckService->delete($command);

        $this->assertTrue(true);
    }
}
