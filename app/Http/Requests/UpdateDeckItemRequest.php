<?php

namespace App\Http\Requests;


use App\Application\Cards\Commands\UpdateDeckItemCommand;
use App\Application\Cards\ValueObjects\CardId;
use App\Application\Cards\ValueObjects\DeckId;
use App\Application\Cards\ValueObjects\DeckItemId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDeckItemRequest extends FormRequest
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
            'deckId' => 'required|string',
            'cardId' => 'required|string',
        ];
    }

    public function getUpdateDeckItemCommand(DeckItemId $deckItemId): UpdateDeckItemCommand
    {
        return new UpdateDeckItemCommand(
            id: $deckItemId,
            deckId: new DeckId($this->input('deckId')),
            cardId: new CardId($this->input('cardId'))
        );
    }
}
