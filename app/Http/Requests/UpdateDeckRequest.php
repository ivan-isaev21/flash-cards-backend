<?php

namespace App\Http\Requests;

use App\Application\Cards\Commands\UpdateDeckCommand;
use App\Application\Cards\Enums\DeckType;
use App\Application\Cards\ValueObjects\DeckId;
use App\Application\Shared\Enums\Locale;
use App\Application\User\ValueObjects\UserId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDeckRequest extends FormRequest
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
            'name' => 'nullable|string',
            'locale' => ['nullable', Rule::in(Locale::cases())],
            'type' => ['nullable', Rule::in(DeckType::cases())]
        ];
    }

    public function getUpdateDeckCommand(DeckId $id, UserId $userId): UpdateDeckCommand
    {
        return new UpdateDeckCommand(
            id: $id,
            createdBy: $userId,
            locale: $this->input('locale') !== null ? Locale::from($this->input('locale')) : null,
            name: $this->input('name') ?? null,
            type: $this->input('type') !== null ? DeckType::from($this->input('type')) : null
        );
    }
}
