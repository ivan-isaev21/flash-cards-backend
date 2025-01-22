<?php

namespace App\Application\Cards\Handlers;

use App\Application\Cards\Commands\SubmitReviewCommand;
use App\Application\Cards\ValueObjects\SpacedRepetitionId;
use App\Domain\Cards\Entities\SpacedRepetition;
use App\Domain\Cards\Exceptions\DeckItemInvalidArgumentException;
use App\Domain\Cards\Exceptions\DeckItemNotFoundException;
use App\Domain\Cards\Repositories\DeckItemRepository;
use App\Domain\Cards\Repositories\SpacedRepetitionRepository;
use DateTimeImmutable;

class SpacedRepetitionHandler
{
    private DeckItemRepository $deckItemrepository;
    private SpacedRepetitionRepository $spacedRepetitionRepository;
    private const MIN_EASINESS = 1.3;
    private const MAX_INTERVAL = 365;

    public function __construct(DeckItemRepository $deckItemrepository, SpacedRepetitionRepository $spacedRepetitionRepository)
    {
        $this->deckItemrepository = $deckItemrepository;
        $this->spacedRepetitionRepository = $spacedRepetitionRepository;
    }

    public function handle(SubmitReviewCommand $command): SpacedRepetition
    {
        $deckItem = $this->deckItemrepository->findDeckItemById($command->deckItemId);

        if ($deckItem === null) {
            throw new DeckItemNotFoundException($command->deckItemId);
        }

        //ToDo find user same

        $spacedRepetition = $this->spacedRepetitionRepository->findSpacedRepetitionByDeckIdAndUserId(deckItemId: $command->deckItemId, userId: $command->userId);

        if ($spacedRepetition === null) {
            $spacedRepetition = new SpacedRepetition(
                id: SpacedRepetitionId::next(),
                deckItem: $deckItem,
                userId: $command->userId
            );
        }

        if ($command->quality < 0 || $command->quality > 5) {
            throw new DeckItemInvalidArgumentException("Quality must be between 0 and 5.");
        }

        $newEasiness = $this->calculateEasiness(spacedRepetition: $spacedRepetition, quality: $command->quality);
        $newRepetitions = $this->calculateRepetitions(spacedRepetition: $spacedRepetition, quality: $command->quality);
        $newInterval = $this->calculateInterval(spacedRepetition: $spacedRepetition, quality: $command->quality, easiness: $newEasiness, repetitions: $newRepetitions);
        $newNextReviewDate = $this->calculateNextReviewDate(interval: $newInterval);

        $spacedRepetition = new SpacedRepetition(
            id: $spacedRepetition->id,
            deckItem: $deckItem,
            userId: $command->userId,
            easiness: $newEasiness,
            repetition: $newRepetitions,
            interval: $newInterval,
            nextDate: $newNextReviewDate
        );

        return $this->spacedRepetitionRepository->save($spacedRepetition);
    }

    private function calculateEasiness(SpacedRepetition $spacedRepetition, int $quality): float
    {
        return max(
            self::MIN_EASINESS,
            $spacedRepetition->easiness + (0.1 - (5 - $quality) * (0.08 + (5 - $quality) * 0.02))
        );
    }

    private function calculateRepetitions(SpacedRepetition $spacedRepetition, int $quality): int
    {
        $repetitions = $spacedRepetition->repetition;

        if ($quality >= 3) {
            $repetitions++;
        } else {
            $repetitions = 0;
        }

        return $repetitions;
    }

    private function calculateInterval(SpacedRepetition $spacedRepetition, int $quality, float $easiness, int $repetitions): int
    {
        if (!($quality >= 3)) {
            return 1;
        }

        $interval = $spacedRepetition->interval;

        if ($repetitions == 1) {
            $interval = 1;
        } elseif ($repetitions == 2) {
            $interval = 6;
        } else {
            $interval = (int) round($interval * $easiness);
        }

        $interval = min($interval, self::MAX_INTERVAL);

        return $interval;
    }

    private function calculateNextReviewDate(int $interval): DateTimeImmutable
    {
        return (new DateTimeImmutable())->modify("+{$interval} days");
    }
}
