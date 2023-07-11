<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Spatie\Permission\Models\Permission;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct(string $moduleName = null)
    {
        if ($moduleName) {
            $modulePermissions = Permission::where('parent_module_name', $moduleName)
                ->get()
                ->toArray();

            foreach ($modulePermissions as $key => $permission) {
                $this->middleware("can:{$permission['name']}", ['only' => [$permission["method"]]]);
            }
        }

    }
}
