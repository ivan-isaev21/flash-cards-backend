<?php

namespace App\Providers;

use App\Domain\Cards\Repositories\CardRepository;
use App\Domain\Cards\Repositories\DeckItemRepository;
use App\Domain\Cards\Repositories\DeckRepository;
use App\Infrastructure\Repositories\EloquentCardRepository;
use App\Infrastructure\Repositories\EloquentDeckItemRepository;
use App\Infrastructure\Repositories\EloquentDeckRepository;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
