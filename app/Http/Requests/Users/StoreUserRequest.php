<?php

namespace App\Http\Requests\Users;

use App\Actions\Common\BaseFormRequest;
use App\Models\User;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
            'roles' => ['array'],
            'status' => ['nullable', 'in:active,inactive']
        ];
    }
}
