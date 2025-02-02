<?php

namespace App\Providers;

use App\Domain\Cards\Repositories\CardRepository;
use App\Domain\Cards\Repositories\DeckItemRepository;
use App\Domain\Cards\Repositories\DeckRepository;
use App\Domain\Cards\Repositories\SpacedRepetitionRepository;
use App\Domain\User\Contracts\LoginTokenGenerator;
use App\Domain\User\Contracts\PasswordHasher;
use App\Domain\User\Contracts\VerifyTokenGenerator;
use App\Domain\User\Repositories\UserRepository;
use App\Infrastructure\LaravelPasswordHasher;
use App\Infrastructure\LaravelVerifyTokenGenerator;
use App\Infrastructure\Repositories\EloquentCardRepository;
use App\Infrastructure\Repositories\EloquentDeckItemRepository;
use App\Infrastructure\Repositories\EloquentDeckRepository;
use App\Infrastructure\Repositories\EloquentSpacedRepetitionRepository;
use App\Infrastructure\Repositories\EloquentUserRepository;
use App\Infrastructure\SanctumLoginTokenGenerator;
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
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
        $this->app->bind(SpacedRepetitionRepository::class, EloquentSpacedRepetitionRepository::class);
        $this->app->bind(PasswordHasher::class, LaravelPasswordHasher::class);
        $this->app->bind(LoginTokenGenerator::class, SanctumLoginTokenGenerator::class);
        $this->app->bind(VerifyTokenGenerator::class, LaravelVerifyTokenGenerator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
