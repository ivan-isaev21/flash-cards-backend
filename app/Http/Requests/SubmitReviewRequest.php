<?php

namespace App\Http\Requests;

use App\Application\Cards\Commands\SubmitReviewCommand;
use App\Application\Cards\ValueObjects\DeckItemId;
use App\Application\User\ValueObjects\UserId;
use Illuminate\Foundation\Http\FormRequest;

class SubmitReviewRequest extends FormRequest
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
            'quality' => 'required|min:1'
        ];
    }

    public function getSubmitReviewCommand(DeckItemId $deckItemId, UserId $userId): SubmitReviewCommand
    {
        return new SubmitReviewCommand(deckItemId: $deckItemId, userId: $userId, quality: $this->input('quality'));
    }
}
