<?php

namespace App\Http\Requests;

use App\Application\Shared\ValueObjects\Email;
use App\Application\User\Commands\RegisterUserCommand;
use App\Infrastructure\PasswordSettings;
use Illuminate\Foundation\Http\FormRequest;

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
                PasswordSettings::make()
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
