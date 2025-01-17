<?php

namespace App\Http\Controllers\Api\v1;

use App\Application\Cards\Commands\DeleteDeckCommand;
use App\Application\Cards\ValueObjects\DeckId;
use App\Domain\Cards\Services\DeckService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDeckRequest;
use App\Http\Requests\GetDecksRequest;
use App\Http\Requests\UpdateDeckRequest;
use App\Http\Resources\DeckResource;
use App\Http\Resources\DeckResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeckController extends Controller
{
    private DeckService $service;

    public function __construct(DeckService $service)
    {
        $this->service = $service;
    }

    public function paginate(GetDecksRequest $request): Response
    {
        return response(new DeckResourceCollection($this->service->paginate($request->getDeckQuery())), Response::HTTP_OK);
    }

    public function create(CreateDeckRequest $request): Response
    {
        return response(new DeckResource($this->service->create($request->getCreateDeckCommand())), Response::HTTP_CREATED);
    }

    public function update(UpdateDeckRequest $request, string $id): Response
    {
        return response(new DeckResource($this->service->update($request->getUpdateDeckCommand($id))), Response::HTTP_ACCEPTED);
    }

    public function delete(string $id)
    {
        $this->service->delete(new DeleteDeckCommand(id: new DeckId($id)));
        return response('', Response::HTTP_NO_CONTENT);
    }
}
