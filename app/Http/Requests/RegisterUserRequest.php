<?php

namespace App\Http\Requests;

use App\Application\Shared\ValueObjects\Email;
use App\Application\User\Commands\RegisterUserCommand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
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
            'email' => 'required|email',
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

    public function getRegisterUserCommand(): RegisterUserCommand
    {
        return new RegisterUserCommand(
            name: $this->input('name'),
            email: new Email($this->input('email')),
            password: $this->input('password')
        );
    }
}
