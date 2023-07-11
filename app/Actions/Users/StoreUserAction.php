<?php

namespace App\Actions\Users;

use App\Actions\Common\AbstractCreateAction;
use App\Models\User;

class StoreUserAction extends AbstractCreateAction
{
    protected string $modelClass = User::class;

    public function create(array $data): User
    {
        /** @var User $user */
        $user = parent::create($data);
        $user->syncRoles($data['roles']);

        return $user;
    }
}
