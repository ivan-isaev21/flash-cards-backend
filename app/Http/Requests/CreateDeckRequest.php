<?php

namespace App\Http\Requests;

use App\Application\Cards\Commands\CreateDeckCommand;
use App\Application\Cards\Enums\DeckType;
use App\Application\Shared\Enums\Locale;
use App\Application\User\ValueObjects\UserId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateDeckRequest extends FormRequest
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
            'name' => 'required|string',
            'locale' => ['required', Rule::in(Locale::cases())],
            'type' => ['required', Rule::in(DeckType::cases())]
        ];
    }

    public function getCreateDeckCommand(): CreateDeckCommand
    {
        return new CreateDeckCommand(
            name: $this->input('name'),
            locale: Locale::from($this->input('locale')),
            type: DeckType::from($this->input('type')),
            createdBy: new UserId('hard-code')
        );
    }
}
