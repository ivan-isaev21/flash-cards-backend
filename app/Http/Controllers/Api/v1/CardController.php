<?php

namespace App\Http\Controllers\Api\v1;

use App\Application\Cards\Commands\DeleteCardCommand;
use App\Application\Cards\Queries\GetCardsQuery;
use App\Application\Cards\ValueObjects\CardId;
use App\Domain\Cards\Services\CardService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCardRequest;
use App\Http\Requests\GetCardsRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Http\Resources\CardResource;
use App\Http\Resources\CardResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CardController extends Controller
{
    private CardService $service;

    public function __construct(CardService $service)
    {
        $this->service = $service;
    }

    public function paginate(GetCardsRequest $request): Response
    {
        return response(new CardResourceCollection($this->service->paginate($request->getCardsQuery()), Response::HTTP_OK));
    }

    public function create(CreateCardRequest $request): Response
    {
        return response(new CardResource($this->service->create($request->getCreateCardCommand())), Response::HTTP_CREATED);
    }

    public function update(UpdateCardRequest $request, string $id): Response
    {
        return response(new CardResource($this->service->update($request->getUpdateCardCommand($id))), Response::HTTP_ACCEPTED);
    }

    public function delete(string $id): Response
    {
        $this->service->delete(new DeleteCardCommand(id: new CardId($id)));
        return response('', Response::HTTP_NO_CONTENT);
    }
}
