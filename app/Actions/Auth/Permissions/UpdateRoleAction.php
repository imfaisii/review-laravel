<?php

namespace App\Actions\Auth\Permissions;

use App\Actions\Common\AbstractUpdateAction;
use App\Actions\Common\BaseModel;
use App\Models\ExtendedRole;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UpdateRoleAction extends AbstractUpdateAction
{
    protected string $modelClass = ExtendedRole::class;

    public function update(BaseModel|Role|Permission $role, array $data): BaseModel|Role|Permission
    {
        $role->syncPermissions([]);
        $role->update($data);
        $role->syncPermissions($data['permissions']);

        return $role;
    }
}
