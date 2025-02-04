<?php

namespace App\Http\Requests;

use App\Application\Shared\ValueObjects\Email;
use App\Application\User\Commands\UpdateUserCommand;
use App\Application\User\ValueObjects\UserId;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'email' => 'required|email'
        ];
    }

    public function getUpdateUserCommand(UserId $id): UpdateUserCommand
    {
        return new UpdateUserCommand(
            id: $id,
            name: $this->input('name'),
            email: new Email($this->input('email'))
        );
    }
}
