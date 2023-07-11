<?php

namespace App\Http\Controllers\Auth\Permissions;

use App\Actions\Auth\Permissions\ListPermissionsAction;
use App\Http\Controllers\Controller;
use App\Traits\Common\HasApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

use function App\Helpers\get_permissions_as_modules_array;

class PermissionController extends Controller
{
    use HasApiResponses;

    public function __construct()
    {
        // parent::__construct(moduleName: "permissions");
    }

    public function index(ListPermissionsAction $action): ResourceCollection
    {
        $action->enableQueryBuilder();
        return $action->resourceCollection(collect(get_permissions_as_modules_array($action->listOrPaginate())));
    }

    public function getUserPermissions(): JsonResponse
    {
        return $this->successResponse(auth()->check() ? auth()->user()->getPermissions() : 0);
    }
}
