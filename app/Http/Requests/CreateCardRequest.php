<?php

namespace App\Http\Requests;

use App\Application\Cards\Commands\CreateCardCommand;
use App\Application\Shared\Enums\Locale;
use App\Application\User\ValueObjects\UserId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCardRequest extends FormRequest
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
            'locale' => ['required', Rule::in(Locale::cases())],
            'question' => 'required|string',
            'answer' => 'required|string',
            'keywords' => 'required|array'
        ];
    }

    public function getCreateCardCommand(): CreateCardCommand
    {
        return new CreateCardCommand(
            locale: Locale::from($this->input('locale')),
            question: $this->input('question'),
            answer: $this->input('answer'),
            keywords: $this->input('keywords'),
            createdBy: UserId::next()
        );
    }
}
