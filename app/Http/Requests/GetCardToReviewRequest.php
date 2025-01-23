<?php

namespace App\Http\Requests;

use App\Application\Cards\Queries\GetCardToReviewQuery;
use App\Application\Cards\ValueObjects\DeckId;
use App\Application\User\ValueObjects\UserId;
use Illuminate\Foundation\Http\FormRequest;

class GetCardToReviewRequest extends FormRequest
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
        return [];
    }

    public function getCardToReviewQuery(DeckId $deckId, UserId $userId): GetCardToReviewQuery
    {
        return new GetCardToReviewQuery(
            deckId: $deckId,
            userId: $userId
        );
    }
}
