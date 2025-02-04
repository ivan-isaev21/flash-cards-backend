<?php

namespace App\Http\Requests;

use App\Application\User\Commands\ChangeUserPasswordCommand;
use App\Application\User\ValueObjects\UserId;
use App\Infrastructure\PasswordSettings;
use Illuminate\Foundation\Http\FormRequest;

class ChangeUserPasswordRequest extends FormRequest
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
            'password' => [
                'required',
                'confirmed',
                PasswordSettings::make()
            ],
        ];
    }

    public function getChangeUserPasswordCommand(UserId $id): ChangeUserPasswordCommand
    {
        return new ChangeUserPasswordCommand(id: $id, password: $this->input('password'));
    }
}
