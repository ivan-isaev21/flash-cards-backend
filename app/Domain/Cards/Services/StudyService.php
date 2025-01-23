<?php

namespace App\Domain\Cards\Services;

use App\Application\Cards\Commands\SubmitReviewCommand;
use App\Application\Cards\Handlers\GetCardToReviewHandler;
use App\Application\Cards\Handlers\SpacedRepetitionHandler;
use App\Application\Cards\Queries\GetCardToReviewQuery;
use App\Domain\Cards\Entities\DeckItem;
use App\Domain\Cards\Entities\SpacedRepetition;
use App\Domain\Cards\Events\ReviewSubmited;

use Illuminate\Contracts\Events\Dispatcher;

class StudyService
{
    private Dispatcher $dispatcher;
    private SpacedRepetitionHandler $spacedRepetitionHandler;
    private GetCardToReviewHandler $getCardToReviewHandler;

    public function __construct(
        Dispatcher $dispatcher,
        SpacedRepetitionHandler $spacedRepetitionHandler,
        GetCardToReviewHandler $getCardToReviewHandler
    ) {
        $this->dispatcher = $dispatcher;
        $this->spacedRepetitionHandler = $spacedRepetitionHandler;
        $this->getCardToReviewHandler = $getCardToReviewHandler;
    }

    public function getCardToReview(GetCardToReviewQuery $query): ?DeckItem
    {
        return $this->getCardToReviewHandler->handle($query);
    }

    public function submitReview(SubmitReviewCommand $command): SpacedRepetition
    {
        $spacedRepetition = $this->spacedRepetitionHandler->handle($command);
        $this->dispatcher->dispatch(new ReviewSubmited(id: $spacedRepetition->id));
        return $spacedRepetition;
    }
}
