<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use function App\Helpers\get_permissions_by_routes;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionsByRoutes = get_permissions_by_routes();

        foreach ($permissionsByRoutes as $module => $subModules) {
            Permission::firstOrCreate(['name' => $module, 'is_module' => true]);

            foreach ($subModules as $key => $subModule) {
                Permission::firstOrCreate([
                    'name' => "{$module}.{$subModule['name']}",
                    'parent_module_name' => $module,
                    'method' => $subModule['method']
                ]);
            }
        }

    }
}
