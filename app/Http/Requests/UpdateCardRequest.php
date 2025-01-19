<?php

namespace App\Http\Requests;

use App\Application\Cards\Commands\UpdateCardCommand;
use App\Application\Cards\ValueObjects\CardId;
use App\Application\Shared\Enums\Locale;
use App\Application\User\ValueObjects\UserId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCardRequest extends FormRequest
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
            'locale' => ['nullable', Rule::in(Locale::cases())],
            'question' => 'nullable|string',
            'answer' => 'nullable|string',
            'keywords' => 'nullable|array'
        ];
    }

    public function getUpdateCardCommand(string $id): UpdateCardCommand
    {
        return new UpdateCardCommand(
            id: new CardId($id),
            createdBy: UserId::next(),
            locale: $this->input('locale') ?? null,
            question: $this->input('question') ?? null,
            answer: $this->input('answer') ?? null,
            keywords: $this->input('keywords') ?? null
        );
    }
}
