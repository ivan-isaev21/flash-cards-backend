<?php

namespace App\Http\Controllers\Api\v1;

use App\Application\Cards\ValueObjects\DeckItemId;
use App\Application\User\ValueObjects\UserId;
use App\Domain\Cards\Services\StudyService;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitReviewRequest;
use App\Http\Resources\DeckItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StudyController extends Controller
{
    private StudyService $service;

    public function __construct(StudyService  $service)
    {
        $this->service = $service;
    }

    public function submitReview(SubmitReviewRequest $request, string $deckItemId): Response
    {
        return response(
            new DeckItemResource(
                $this->service->submitReview(
                    $request->getSubmitReviewCommand(
                        deckItemId: new DeckItemId($deckItemId),
                        userId: new UserId('e91294bf-46e5-33b7-94fc-ad50d5675022')
                    )
                )
            ),
            Response::HTTP_ACCEPTED
        );
    }
}
