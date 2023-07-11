<?php

namespace App\Http\Controllers\Auth\Permissions;

use App\Actions\Auth\Permissions\DeleteRoleAction;
use App\Actions\Auth\Permissions\ListRoleAction;
use App\Actions\Auth\Permissions\StoreRoleAction;
use App\Actions\Auth\Permissions\UpdateRoleAction;
use App\Enums\Auth\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Permissions\StoreRoleRequest;
use App\Http\Requests\Auth\Permissions\UpdateRoleRequest;
use App\Models\ExtendedRole;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\Permission\Models\Role;

use function App\Helpers\null_resource;

class RoleController extends Controller
{
    public function index(ListRoleAction $action): ResourceCollection
    {
        $action->enableQueryBuilder();
        return $action->resourceCollection($action->listOrPaginate());
    }

    public function store(StoreRoleRequest $request, StoreRoleAction $action): JsonResource
    {
        $role = $action->create($request->validated());
        return $action->individualResource($role);
    }

    public function update(Role $role, UpdateRoleAction $action, UpdateRoleRequest $request): JsonResource
    {
        $role = $action->update($role, $request->validated());
        return $action->individualResource($role);
    }

    public function destroy(Role $role, DeleteRoleAction $action): JsonResource
    {
        if ($role->name !== RolesEnum::SUPER_ADMIN) {
            $action->delete($role);
        }

        return null_resource();
    }
}
