<?php

namespace App\Http\Controllers\Api\v1;

use App\Application\Cards\Commands\DeleteDeckItemCommand;
use App\Application\Cards\ValueObjects\DeckId;
use App\Application\Cards\ValueObjects\DeckItemId;
use App\Domain\Cards\Services\DeckItemService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDeckItemRequest;
use App\Http\Requests\GetDeckItemsRequest;
use App\Http\Requests\UpdateDeckItemRequest;
use App\Http\Resources\DeckItemResource;
use App\Http\Resources\DeckItemResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class DeckItemController extends Controller
{
    private DeckItemService $service;

    public function __construct(DeckItemService $service)
    {
        $this->service = $service;
    }

    public function paginate(GetDeckItemsRequest $request, string $deckId): Response
    {
        return response(new DeckItemResourceCollection($this->service->paginate($request->getDeckItemsQuery(new DeckId($deckId)))), Response::HTTP_OK);
    }

    public function create(CreateDeckItemRequest $request): Response
    {
        return response(new DeckItemResource($this->service->create($request->getCreateDeckItemCommand())), Response::HTTP_CREATED);
    }

    public function update(UpdateDeckItemRequest $request, string $id): Response
    {
        return response(new DeckItemResource($this->service->update($request->getUpdateDeckItemCommand(new DeckItemId($id)))), Response::HTTP_ACCEPTED);
    }

    public function delete(string $id)
    {
        $this->service->delete(new DeleteDeckItemCommand(id: new DeckItemId($id)));
        return response('', Response::HTTP_NO_CONTENT);
    }
}
