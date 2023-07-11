<?php

namespace App\Http\Controllers;

use App\Actions\Common\BaseJsonResource;
use App\Actions\Users\DeleteUserAction;
use App\Actions\Users\FindUserAction;
use App\Actions\Users\ListUserAction;
use App\Actions\Users\StoreUserAction;
use App\Http\Requests\Users\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

use function App\Helpers\null_resource;

class UserController extends Controller
{
    public function index(ListUserAction $action): ResourceCollection
    {
        $action->enableQueryBuilder();
        return $action->resourceCollection($action->listOrPaginate());
    }

    public function store(StoreUserRequest $request, StoreUserAction $action): JsonResource
    {
        $user = $action->create($request->validated());
        return $action->individualResource($user);
    }

    public function show(int $id, FindUserAction $action): BaseJsonResource
    {
        $action->enableQueryBuilder();
        return $action->individualResource($action->findOrFail($id));
    }

    public function destroy(User $user, DeleteUserAction $action): BaseJsonResource
    {
        $action->delete($user);
        return null_resource();
    }
}
