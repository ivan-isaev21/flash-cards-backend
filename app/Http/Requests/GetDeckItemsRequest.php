<?php

namespace App\Http\Requests;

use App\Application\Cards\Queries\GetDeckItemsQuery;
use App\Application\Cards\Queries\GetDecksQuery;
use App\Application\Cards\ValueObjects\DeckId;
use Illuminate\Foundation\Http\FormRequest;

class GetDeckItemsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => 'nullable|numeric',
            'perPage' => 'nullable|numeric'
        ];
    }

    public function getDeckItemsQuery(DeckId $deckId): GetDeckItemsQuery
    {
        return new GetDeckItemsQuery(
            deckId: $deckId,
            page: $this->input('page') ?? 1,
            perPage: $this->input('perPage') ?? 15
        );
    }
}
