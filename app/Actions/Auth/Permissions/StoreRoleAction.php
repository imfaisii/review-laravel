<?php

namespace App\Actions\Auth\Permissions;

use App\Actions\Common\AbstractCreateAction;
use Spatie\Permission\Models\Role;

class StoreRoleAction extends AbstractCreateAction
{
    protected string $modelClass = Role::class;

    public function create(array $data): Role
    {
        /** @var Role $role */
        $role = parent::create($data);  
        $role->syncPermissions($data['permissions']);

        return $role;
    }
}
