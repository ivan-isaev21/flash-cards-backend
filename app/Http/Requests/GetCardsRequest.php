<?php

namespace App\Http\Requests;

use App\Application\Cards\Queries\GetCardsQuery;
use Illuminate\Foundation\Http\FormRequest;

class GetCardsRequest extends FormRequest
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

    public function getCardsQuery(): GetCardsQuery
    {
        return new GetCardsQuery(
            page: $this->input('page') ?? 1,
            perPage: $this->input('per_page') ?? 15
        );
    }
}
