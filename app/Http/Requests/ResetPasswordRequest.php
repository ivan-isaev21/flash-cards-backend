<?php

namespace App\Http\Requests;

use App\Application\Shared\ValueObjects\Email;
use App\Application\User\Commands\ResetUserPasswordCommand;
use App\Infrastructure\PasswordSettings;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'token' => 'required|string',
            'password' => [
                'required',
                'confirmed',
                PasswordSettings::make()
            ],
        ];
    }

    public function getResetUserPasswordCommand(): ResetUserPasswordCommand
    {
        return new ResetUserPasswordCommand(
            email: new Email($this->input('email')),
            token: $this->input('token'),
            password: $this->input('password')
        );
    }
}
