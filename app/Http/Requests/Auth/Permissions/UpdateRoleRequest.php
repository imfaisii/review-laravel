<?php

namespace App\Http\Requests\Auth\Permissions;

use App\Actions\Common\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('roles')->ignore($this->route('role')),
            ],
            'permissions' => ['array']
        ];
    }
}
