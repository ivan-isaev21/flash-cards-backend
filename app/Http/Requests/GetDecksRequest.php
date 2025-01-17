<?php

namespace App\Http\Requests;

use App\Application\Cards\Queries\GetDecksQuery;
use Illuminate\Foundation\Http\FormRequest;

class GetDecksRequest extends FormRequest
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

    public function getDeckQuery(): GetDecksQuery
    {
        return new GetDecksQuery(
            page: $this->input('page') ?? 1,
            perPage: $this->input('perPage') ?? 15
        );
    }
}
