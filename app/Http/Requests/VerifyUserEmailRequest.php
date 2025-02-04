<?php

namespace App\Http\Requests;

use App\Application\Shared\ValueObjects\Email;
use App\Application\User\Commands\VerifyUserEmailCommand;
use Illuminate\Foundation\Http\FormRequest;

class VerifyUserEmailRequest extends FormRequest
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
            'email' => 'required|email',
            'token' => 'required|string'
        ];
    }

    public function getVerifyUserEmailCommand(): VerifyUserEmailCommand
    {
        return new VerifyUserEmailCommand(
            email: new Email($this->input('email')),
            token: $this->input('token')
        );
    }
}
