<?php

namespace App\Providers;

use App\Application\User\Contracts\PasswordHasher;
use App\Application\User\Contracts\TokenGenerator;
use App\Domain\Cards\Repositories\CardRepository;
use App\Domain\Cards\Repositories\DeckItemRepository;
use App\Domain\Cards\Repositories\DeckRepository;
use App\Domain\Cards\Repositories\SpacedRepetitionRepository;
use App\Infrastructure\LaravelPasswordHasher;
use App\Infrastructure\Repositories\EloquentCardRepository;
use App\Infrastructure\Repositories\EloquentDeckItemRepository;
use App\Infrastructure\Repositories\EloquentDeckRepository;
use App\Infrastructure\Repositories\EloquentSpacedRepetitionRepository;
use App\Infrastructure\SanctumTokenGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CardRepository::class, EloquentCardRepository::class);
        $this->app->bind(DeckRepository::class, EloquentDeckRepository::class);
        $this->app->bind(DeckItemRepository::class, EloquentDeckItemRepository::class);
        $this->app->bind(SpacedRepetitionRepository::class, EloquentSpacedRepetitionRepository::class);
        $this->app->bind(PasswordHasher::class, LaravelPasswordHasher::class);
        $this->app->bind(TokenGenerator::class, SanctumTokenGenerator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
