<?php

namespace App\Helpers;

use App\Actions\Common\BaseJsonResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

function null_resource(): JsonResource
{
    return new BaseJsonResource(null);
}

function get_permissions_by_routes(): array
{
    $routeCollection = Route::getRoutes()->get();
    $permissions = [];

    foreach ($routeCollection as $item) {
        $name = $item->action;
        if (!empty($name['as'])) {
            $permission = $name['as'];
            $permission = trim(strtolower($permission));
            $ignoreRoutesStartingWith = 'sanctum|livewire|ignition';
            $permissionFilled = trim(str_replace("user management ", "", $permission));
            if (preg_match("($ignoreRoutesStartingWith)", $permission) === 0 && filled($permissionFilled)) {
                $method = $item->getActionMethod();

                if (strpos($method, '\\') !== false) {
                    $method = "__invoke";
                }

                $permissions[] = ["name" => $permissionFilled, "method" => $method];
            }
        }
    }

    return get_modules_array_from_permissions($permissions);
}

function get_modules_array_from_permissions(array $permissions): array
{
    $modules = [];

    foreach ($permissions as $item) {
        $parts = explode('.', $item['name']);
        $module = $parts[0];
        $submodule = implode('.', array_slice($parts, 1));

        if (!isset($modules[$module])) {
            $modules[$module] = [];
        }

        if (!in_array($submodule, $modules[$module])) {
            array_push($modules[$module], ["name" => $submodule, "method" => $item['method']]);
        }
    }

    foreach ($modules as &$submodules) {
        sort($submodules);
    }

    return $modules;
}

function get_all_includes(): array
{
    $includes = request()->input('include');
    if ($includes === null) {
        return [];
    } elseif (is_array($includes)) {
        return $includes;
    } else {
        return explode(',', $includes);
    }
}

function get_all_includes_in_camel_case(): array
{
    return collect(get_all_includes())
        ->map(function (string $includes) {
            return collect(explode('.', $includes))
                ->map(fn (string $include) => Str::camel($include))
                ->join('.');
        })
        ->toArray();
}

function get_all_appends(): array
{
    $appends = request()->input('append');
    if ($appends === null) {
        return [];
    } elseif (is_array($appends)) {
        return $appends;
    } else {
        return explode(',', $appends);
    }
}

function is_include_present(string $include): bool
{
    return in_array(Str::snake($include), get_all_includes());
}

function get_permissions_as_modules_array(mixed $permissions): array
{
    $finalPermissions = [];
    $modules = $permissions->where('parent_module_name', null)->pluck('name')->toArray();
    $modulesThroughSubmodules = $permissions->pluck('name');

    foreach ($modulesThroughSubmodules as $key => $submodule) {
        try {
            $moduleName = explode(".", $submodule)[0];
            if (!in_array($moduleName, $modules)) {
                $modules[] = $moduleName;
            }
        } catch (\Exception $e) {
            //
        }
    }

    foreach ($modules as $module) {
        $modulePermissions = $permissions->filter(function ($permission) use ($module) {
            return strpos($permission['name'], $module) === 0 && $permission['name'] !== $module;
        })->map(function ($permission) use ($module) {
            return [
                'id' => $permission['id'],
                'name' => Str::ucfirst(Str::replace(".", " ", Str::replace("{$module}.", "", $permission['name']))),
            ];
        })->toArray();

        $moduleObject = [
            'name' => $module,
            'submodules' => array_values($modulePermissions),
        ];

        $finalPermissions[] = $moduleObject;
    }

    return $finalPermissions;
}
