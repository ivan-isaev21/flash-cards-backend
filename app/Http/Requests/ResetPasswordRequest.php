<?php

namespace App\Http\Requests;

use App\Application\Shared\ValueObjects\Email;
use App\Application\User\Commands\ResetUserPasswordCommand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

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
                Password::min(8)                  // Минимальная длина
                    ->letters()             // Должны быть буквы
                    ->mixedCase()          // Должны быть буквы в разном регистре
                    ->numbers()            // Должны присутствовать цифры
                    ->symbols()            // Должны присутствовать символы
                    ->uncompromised(),     // Проверка на утечки (haveibeenpwned)
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
